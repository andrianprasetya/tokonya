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
use App\Http\Resources\ProductIdsResource;
use App\Models\ProductId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use App\Libraries\ResponseStd;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ProductController extends BaseApi
{
    protected function validateReservationProduct(array $data)
    {
        $arrayValidator = [
            'product_name' => [
                'required',
                'min:3',
                'max:70',
                'unique:product_ids,product_name,NULL,id,merchant_id,' . auth()->user()->id
            ],
        ];

        return Validator::make($data, $arrayValidator);
    }

    protected function createReservationProduct(array $data, Request $request)
    {
        $model = ProductId::query()->create([
            'id' => Uuid::uuid4()->toString(),
            'merchant_id' => auth()->user()->id,
            'product_name' => $request->input('product_name')
        ]);

        return $model;
    }

    /**
     * Store a new records.
     *
     * @param Request $request
     * @return array|\Illuminate\Http\Response
     */
    public function storeProductIds(Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $this->validateReservationProduct($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
            $model = $this->createReservationProduct($request->all(), $request);
            DB::commit();

            // return response.
            $single = new ProductIdsResource($model);
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
                    return ResponseStd::fail($e->getMessage());
                }
            }
        }
    }

    public function storeProduct($id, Request $request)
    {

    }
}
