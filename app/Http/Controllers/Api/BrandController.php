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
use App\Http\Resources\BrandResource;
use App\Libraries\FilesLibrary;
use App\Libraries\NumberLibrary;
use App\Libraries\ResponseStd;
use App\Models\Brand;
use App\Models\FileModel;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;

class BrandController extends BaseApi
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

            $sort = $request->has('sort') ? $request->input('sort') : 'brands.created_at';

            $order = $request->has('order') ? $request->input('order') : 'DESC';

            $is_active = $request->has('is_active') ? (int)$request->input('is_active') : 1;

            $conditions = '1 = 1';

            if (!empty($search_term)) {
                $conditions .= " AND brands.category_name ILIKE '%$search_term%'";
                $conditions .= " OR brands.brand_code ILIKE '%$search_term%'";
            }

            $conditions .= " AND brands.is_active = $is_active";

            $merchantId = auth()->user()->id;

            $conditions .= " AND brands.merchant_id = '{$merchantId}'";

            // force limit
            if ($limit > 50) {
                $limit = 50;
            }

            $select = Brand::query()
                ->select([
                    'brands.*',
                ])
                ->whereRaw($conditions)
                ->orderBy($sort, $order);

            // counting all brands
            $countAll = Brand::query()->count();

            // Pagination brands
            $paginate = $select->paginate($limit);

            // paging response.
            $response = BrandResource::collection($paginate);

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
     * Create a new record.
     *
     * @param array $data
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    protected function create(array $data, Request $request)
    {
        $description = !empty($data['brand_description']) ? $data['brand_description'] : null;
        $code = !empty($data['brand_code']) ? $data['brand_code'] : NumberLibrary::randomName($data['brand_name']);

        // Input data image
        $dataImageId = null;

        foreach ($request->file() as $key => $file) {
            if ($request->hasFile($key)) {
                if ($request->file($key)->isValid()) {
                    $imageId = (new FilesLibrary())
                        ->saveImage(
                            $request->file($key),
                            'images/brand',
                            false,
                            300,
                            300,
                            'brand'
                        );
                    $dataImageId = $imageId;
                }
            } else {
                $key_id = !empty($request->$key . '_old') ? $request->$key . '_old' : null;
                $dataImageId = $key_id;
            }
        }

        // create a new brand
        $model = Brand::query()->create([
            'id' => Uuid::uuid4()->toString(),
            'merchant_id' => auth()->user()->id,
            'brand_code' => $code,
            'brand_name' => $data['brand_name'],
            'brand_description' => $description,
            'is_active' => $data['is_active'],
            'image_id' => $dataImageId,
            'created_at' => Carbon::now(),
        ]);

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
            'brand_name' => [
                'required',
                'min:3',
                'max:70',
                'unique:brands,brand_name,NULL,id,merchant_id,' . auth()->user()->id . ',deleted_at,NULL'
            ],
            'brand_description' => ['required', 'min:10'],
            'is_active' => ['required', 'integer', 'min:0', 'max:1'],
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
            $single = new BrandResource($model);
            return ResponseStd::okSingle($single);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
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
        $model = Brand::query()->find($id);
        if (!$model) {
            throw new \Exception("Invalid data.", 400);
        }
        if ($model->product->count() > 0) {
            throw new \Exception("Cannot delete, brand has attached on products", 406);
        }

        $image_url = null;
        $hasImage = !empty($model->image_id) ? true : false;
        if ($hasImage) {
            $fileId = $model->image_id;
            $file = FileModel::query()->find($fileId);
            if (!$file) {
                throw new \Exception("Invalid Image Id", 406);
            }
            $model->update([
                'image_id' => null,
            ]);
            $model->image()->forceDelete();

            // ========= START DELETE PHYSICAL FILE ========== //
            // Delete physical image using raw query cause getFileUrlAttribute() on FileModel
            $results = DB::select(DB::raw("SELECT file_url FROM files WHERE id = '$fileId'"));
            Storage::disk()->delete($results[0]->file_url);
            // ========= END DELETE PHYSICAL FILE ========== //

            // Delete file
            $file->forceDelete();
        }

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
            $single = new BrandResource($model);
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
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @param array $data
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\Response
     * @throws \Exception
     */
    protected function edit($id, array $data, Request $request)
    {
        $description = !empty($data['brand_description']) ? $data['brand_description'] : null;
        $isActive = !empty($data['is_active']) ? $data['is_active'] : 0;

        // Find by id
        $model = Brand::query()->find($id);

        if (empty($model)) {
            throw new \Exception("Invalid data.");
        }

        // Input data image
        $dataImageId = null;
        foreach ($request->file() as $key => $file) {
            if ($request->hasFile($key)) {
                if ($request->file($key)->isValid()) {
                    $imageId = (new FilesLibrary())
                        ->saveImage(
                            $request->file($key),
                            'images/brand',
                            false,
                            300,
                            300,
                            'brand'
                        );
                    $dataImageId = $imageId;
                }
            }
        }
        if ($dataImageId !== null) {
            // Updating image.
            $model->image_id = $dataImageId;
        }

        $model->brand_description = $description;
        $model->is_active = $isActive;

        //Save
        $model->save();

        return $model;
    }

    /**
     * Validate update.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validateUpdate(array $data)
    {
        $arrayValidator = [
            'brand_description' => ['required', 'min:10'],
            'is_active' => ['required', 'integer', 'min:0', 'max:1'],
        ];

        return Validator::make($data, $arrayValidator);
    }

    /**
     * Update record.
     *
     * @param $id
     * @param Request $request
     * @return array|\Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $this->validateUpdate($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
            Log::debug(json_encode($request->all()));
            $model = $this->edit($id, $request->all(), $request);

            DB::commit();

            // return.
            $single = new BrandResource($model);
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
                    return ResponseStd::fail($e->getMessage());
                }
            }
        }
    }
}
