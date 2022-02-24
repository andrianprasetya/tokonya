<?php
/**
 * Copyright 2022 Odenktools Technology Open Source Project
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files
 * (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge
 * publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO
 * THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
 * CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApi;
use App\Http\Resources\RackResource;
use App\Libraries\ResponseStd;
use App\Models\Product;
use App\Models\Rack;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;

class RackController extends BaseApi
{
    /**
     * List data.
     *
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $search_term = $request->input('search');

            // default data size to display
            $limit = $request->has('limit') ? (int)$request->input('limit') : 50;
            $sort = $request->has('sort') ? $request->input('sort') : 'racks.created_at';
            $order = $request->has('order') ? $request->input('order') : 'DESC';
            $conditions = '1 = 1';

            if (!empty($search_term)) {
                $conditions .= " AND racks.rack_name ILIKE '%$search_term%'";
            }

            $merchantId = auth()->user()->id;

            $conditions .= " AND racks.merchant_id = '{$merchantId}'";

            // force limit
            if ($limit > 50) {
                $limit = 50;
            }

            $select = Rack::query()
                ->select([
                    'racks.*',
                ])
                ->whereRaw($conditions)
                ->orderBy($sort, $order);

            // counting all models
            $countAll = Rack::query()->count();

            // Pagination brands
            $paginate = $select->paginate($limit);

            // paging response.
            $response = RackResource::collection($paginate);

            // return json response.
            return ResponseStd::pagedFrom($response, $paginate, $countAll, 200);
        } catch (\Exception $e) {
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                if ($e instanceof QueryException) {
                    return ResponseStd::fail(trans('error.global.invalid-query'));
                } else {
                    Log::error(__CLASS__ . ":" . $e->getLine() . ':' . __FUNCTION__ . ' ' . $e->getMessage());
                    return ResponseStd::fail($e->getMessage(), $e->getCode());
                }
            }
        }
    }

    /**
     * @param $rackId
     * @param array $data
     * @param bool $update
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     * @throws \Exception
     */
    protected function attachProduct($rackId, array $data, $update = false)
    {
        $timeNow = Carbon::now();
        $authId = auth()->user()->id;
        $model = Rack::query()
            ->where('id', '=', $rackId)
            ->where('merchant_id', '=', $authId)
            ->first();
        if (!$model) {
            throw new \Exception("rack not found");
        }
        if (!empty($data['products'])) {
            $syncMany = [];
            foreach ($data['products'] as $product) {
                $product = Product::query()
                    ->where('id', '=', $product['id'])
                    ->where('merchant_id', '=', $authId)
                    ->first();
                if (!$product) {
                    throw new \Exception("product not found");
                }
                if (!$update) {
                    if ($product->racks->count() > 0) {
                        throw new \Exception("Product is already on the rack.");
                    }
                }
                $tempExtra = [];
                $tempExtra['id'] = Uuid::uuid4()->toString();
                $tempExtra['merchant_id'] = $authId;
                $tempExtra['rack_name'] = $model->rack_name;
                $tempExtra['product_name'] = $product->product_name;
                $tempExtra['created_at'] = $timeNow;
                $tempExtra['updated_at'] = $timeNow;
                $syncMany[$product->id] = $tempExtra;
            }
            $model->products()->sync($syncMany);
        }

        return $model;
    }

    /**
     * Create a new record.
     *
     * @param array $data
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    protected function create(array $data, Request $request)
    {
        $timeNow = Carbon::now();
        $id = Uuid::uuid4()->toString();
        Rack::query()->create([
            'id' => $id,
            'merchant_id' => auth()->user()->id,
            'rack_name' => $data['rack_name'],
            'created_at' => $timeNow,
        ]);

        $model = $this->attachProduct($id, $data, false);

        return $model;
    }

    /**
     * Validate create.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function createValidator(array $data)
    {
        $arrayValidator = [
            'rack_name' => [
                'required',
                'min:3',
                'max:50',
                'unique:racks,rack_name,NULL,id,merchant_id,' . auth()->user()->id
            ],
            'products' => ['array'],
            'products.*.id' => ['required', 'uuid']
        ];

        return Validator::make($data, $arrayValidator);
    }

    /**
     * Store a new records.
     *
     * @param Request $request
     * @return array|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $validate = $this->createValidator($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
            $model = $this->create($request->all(), $request);
            DB::commit();

            // return response.
            $single = new RackResource($model);
            return ResponseStd::okSingle($single);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                Log::error(__CLASS__ . ":" . $e->getLine() . ':' . __FUNCTION__ . ' ' . $e->getMessage());
                if ($e instanceof QueryException) {
                    return ResponseStd::fail(trans('error.global.invalid-query'));
                } else {
                    Log::error(__CLASS__ . ":" . $e->getLine() . ':' . __FUNCTION__ . ' ' . $e->getMessage());
                    return ResponseStd::fail($e->getMessage());
                }
            }
        }
    }

    /**
     * Delete a model.
     *
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     * @throws \Exception
     */
    protected function delete($id)
    {
        $model = Rack::query()->find($id);
        if (!$model) {
            throw new \Exception("Invalid data.", 400);
        }
        //remove products from rack
        $model->products()->detach();

        // remove rack
        $model->delete();

        // return.
        return $model;
    }

    /**
     * Delete a model.
     *
     * @param $id
     * @return array|\Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $model = $this->delete($id);
            DB::commit();

            //return
            $single = new RackResource($model);
            return ResponseStd::okSingle($single);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                Log::error(__CLASS__ . ":" . __FUNCTION__ . ' ' . $e->getMessage());
                if ($e instanceof QueryException) {
                    return ResponseStd::fail(trans('error.global.invalid-query'));
                } else {
                    return ResponseStd::fail($e->getMessage(), $e->getCode());
                }
            }
        }
    }

    /**
     * Validate create.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function updateValidator(array $data)
    {
        $arrayValidator = [
            'products' => ['array'],
            'products.*.id' => ['required', 'uuid']
        ];

        return Validator::make($data, $arrayValidator);
    }

    /**
     * Update racks.
     *
     * @param $id
     * @param Request $request
     * @return array|\Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $this->updateValidator($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
            $model = Rack::query()->find($id);
            if (!$model) {
                throw new \Exception("Invalid data");
            }
            $this->attachProduct($id, $request->all(), true);
            DB::commit();
            return ResponseStd::okSingle($model);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                Log::error(__CLASS__ . ":" . $e->getLine() . ':' . __FUNCTION__ . ' ' . $e->getMessage());
                return ResponseStd::fail($e->getMessage());
            }
        }
    }
}
