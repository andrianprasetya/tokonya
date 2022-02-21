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
use App\Libraries\FilesLibrary;
use App\Libraries\ResponseStd;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Constants;
use App\Models\Merchant;
use App\Models\Product;
use App\Models\ProductId;
use App\Models\ProductVariant;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;

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
            'image_id' => ['required']
        ];

        return Validator::make($data, $arrayValidator);
    }

    /**
     * @param array $data
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    protected function createReservationProduct(array $data, Request $request)
    {
        $imageId = null;
        foreach ($request->file() as $key => $file) {
            if ($request->hasFile($key)) {
                if ($request->file($key)->isValid()) {
                    $imageId = (new FilesLibrary())
                        ->saveImage(
                            $request->file($key),
                            'images/product',
                            false,
                            300,
                            300,
                            'product'
                        );
                }
            }
        }

        $validateProductName = Product::query()
            ->where('product_name', '=', $data['product_name'])
            ->where('merchant_id', '=', auth()->user()->id)
            ->first();

        if ($validateProductName) {
            throw new \Exception("The product name has already been taken.");
        }

        $model = ProductId::query()->create([
            'id' => Uuid::uuid4()->toString(),
            'merchant_id' => auth()->user()->id,
            'product_name' => $data['product_name'],
            'image_id' => $imageId,
        ]);

        return $model;
    }

    /**
     * Store a new product_id records.
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

    /**
     * Validate product.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function createProductValidator(array $data)
    {
        $arrayValidator = [
            'category_id' => ['required', 'uuid'],
            'product_price' => ['required', 'numeric', 'between:1,2000000000'],
            'product_description' => ['required'],
            'stock' => ['integer', 'min:0', 'max:1000000'],
            'subtract' => ['required', 'integer', 'min:0', 'max:1'],
            'is_pre_order' => ['required', 'integer', 'min:0', 'max:1'],
            'is_active' => ['required', 'integer', 'min:0', 'max:1'],
            'weight' => ['required', 'integer', 'min:10', 'max:1000000'],
            'weight_length' => ['required', Rule::in(Constants::PRODUCT_WEIGHT)],
            'product_condition' => ['required', Rule::in(Constants::PRODUCT_CONDITIONS)],
            'dimension_width' => ['present', 'integer', 'min:0', 'max:1000'],
            'dimension_height' => ['present', 'integer', 'min:0', 'max:1000'],
            'dimension_length' => ['present', 'integer', 'min:0', 'max:1000'],
        ];

        return Validator::make($data, $arrayValidator);
    }

    /**
     * @param $id
     * @param array $data
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    protected function createProduct($id, array $data, Request $request)
    {
        $productIds = ProductId::query()->find($id);
        if (!$productIds) {
            throw new \Exception('product not found');
        }
        $category = Category::query()->find($data['category_id']);
        if (!$category) {
            throw new \Exception('category not found');
        }
        $merchant = Merchant::query()->find($productIds->merchant_id);
        if (!$merchant) {
            throw new \Exception('merchant not found');
        }
        $brand = null;
        if (!empty($data['brand_id'])) {
            $dataBrand = Brand::query()->find($data['brand_id']);
            if (!$dataBrand) {
                throw new \Exception('brand not found');
            }
            $brand = $dataBrand->id;
        }

        $sku = !empty($data['sku']) ? $data['sku'] : null;

        $preOrder = !empty($data['pre_order_period']) ? $data['pre_order_period'] : null;
        $pre_order_length = !empty($data['pre_order_length']) ? $data['pre_order_length'] : null;

        $dimensionWidth = !empty($data['dimension_width']) ? $data['dimension_width'] : 0;
        $dimensionHeight = !empty($data['dimension_height']) ? $data['dimension_height'] : 0;
        $dimensionLength = !empty($data['dimension_length']) ? $data['dimension_length'] : 0;

        // Input data image
        $dataImageId = $productIds->image_id;
        $dataImageId2 = null;
        $dataImageId3 = null;
        $dataImageId4 = null;

        foreach ($request->file() as $key => $file) {
            if ($request->hasFile($key)) {
                if ($request->file($key)->isValid()) {
                    if ($key === 'image_id2') {
                        $dataImageId2 = (new FilesLibrary())
                            ->saveImage(
                                $request->file($key),
                                'images/product',
                                false,
                                300,
                                300,
                                'product'
                            );
                    }
                    if ($key === 'image_id3') {
                        $dataImageId3 = (new FilesLibrary())
                            ->saveImage(
                                $request->file($key),
                                'images/product',
                                false,
                                300,
                                300,
                                'product'
                            );
                    }
                    if ($key === 'image_id4') {
                        $dataImageId4 = (new FilesLibrary())
                            ->saveImage(
                                $request->file($key),
                                'images/product',
                                false,
                                300,
                                300,
                                'product'
                            );
                    }
                }
            }
        }

        $model = Product::query()->create([
            'id' => $id,
            'merchant_id' => $merchant->id,
            'merchant_code' => $merchant->merchant_code,
            'product_name' => $productIds->product_name,
            'category_id' => $category->id,
            'category_code' => $category->category_code,
            'product_price' => (float)$data['product_price'],
            'stock' => (int)$data['stock'],
            'subtract' => (int)$data['subtract'],
            'brand_id' => $brand,
            'sku' => $sku,
            'product_description' => $data['product_description'],
            'product_rating' => 0.0,
            'product_condition' => $data['product_condition'],
            'weight' => $data['weight'],
            'weight_length' => $data['weight_length'],

            'dimension_width' => $dimensionWidth,
            'dimension_height' => $dimensionHeight,
            'dimension_length' => $dimensionLength,

            'is_pre_order' => $data['is_pre_order'],
            'pre_order_period' => $preOrder,
            'pre_order_length' => $pre_order_length,
            'is_active' => $data['is_active'],

            'image_id' => $dataImageId,
            'image_id2' => $dataImageId2,
            'image_id3' => $dataImageId3,
            'image_id4' => $dataImageId4,
            'created_at' => Carbon::now(),
        ]);

        ProductId::query()->find($id)->delete();

        return $model;
    }

    /**
     * @param $id
     * @param Request $request
     * @return array|\Illuminate\Http\Response
     */
    public function storeProduct($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $this->createProductValidator($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
            $model = $this->createProduct($id, $request->all(), $request);
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

    protected function createVariantValidator(array $data)
    {
        $arrayValidator = [
            'variants.*.variant_name' => ['required', 'min:1', 'max:30'],
            'variants.*.sub_variant_name' => ['required', 'min:1', 'max:30'],
            'variants.*.variant_price' => ['required', 'numeric', 'between:1,2000000000'],
            'variants.*.variant_stock' => ['required', 'integer', 'min:0', 'max:1000'],
            'variants.*.variant_subtract' => ['required', 'integer', 'min:0', 'max:1'],
            'variants.*.is_active' => ['required', 'integer', 'min:0', 'max:1'],
        ];

        return Validator::make($data, $arrayValidator);
    }

    protected function createVariant($id, array $data, Request $request)
    {
        $variants = $data['variants'];
        $arrayData = null;
        if (!empty($variants && is_array($variants))) {
            $merchantId = auth()->user()->id;
            foreach ($variants as $variant) {
                $imageVariant = null;

                foreach ($request->file() as $key => $file) {
                    if ($request->hasFile($key)) {
                        if ($request->file($key)->isValid()) {
                            $imageVariant = (new FilesLibrary())
                                ->saveImage(
                                    $request->file($key),
                                    'images/variant',
                                    false,
                                    300,
                                    300,
                                    'variant'
                                );
                        }
                    }
                }

                $arrayData = ProductVariant::query()->create([
                    'id' => Uuid::uuid4()->toString(),
                    'product_id' => $id,
                    'merchant_id' => $merchantId,
                    'variant_name' => $variant['variant_name'],
                    'sub_variant_name' => $variant['sub_variant_name'],
                    'sort_order' => 0,
                    'is_active' => (int)$variant['is_active'],
                    'variant_image_id' => $imageVariant,
                    'variant_subtract' => $variant['variant_subtract'],
                    'variant_sku' => $variant['variant_sku'],
                    'variant_price' => (float)$variant['variant_price'],
                    'variant_stock' => (int)$variant['variant_stock'],
                    'created_at' => Carbon::now(),
                ]);
            }

            return $arrayData;
        }
    }

    public function storeVariant($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $this->createVariantValidator($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
            $model = $this->createVariant($id, $request->all(), $request);
            DB::commit();

            return ResponseStd::okSingle($model);
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
}
