<?php

use Illuminate\Database\Seeder;

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
class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timeNow = \Illuminate\Support\Carbon::now();

        $productId = 'ee1e2361-1be2-4e3e-dca1-213fc23e6572';
        $productName = 'PowerBank Zeus Technical';
        $imageId = '52abcde4-4de9-46d9-8bca-580d1ea58bf6';
        $merchantId = 'ff1f2363-9be3-4f3e-aca3-218fc23e6570';

        \App\Models\FileModel::query()->create([
            'id' => '52abcde4-4de9-46d9-8bca-580d1ea58bf6',
            'module_type' => 'product',
            'extension' => 'png',
            'path' => 'public/images/product',
            'file_url' => 'public/images/product/52abcde4-4de9-46d9-8bca-580d1ea58bf6.png',
            'created_at' => $timeNow,
        ]);

        \App\Models\ProductId::query()->create([
            'id' => $productId,
            'merchant_id' => $merchantId,
            'product_name' => $productName,
            'image_id' => $imageId,
        ]);

        \App\Models\Variant::query()->create([
            'id' => 'ff1d2363-9be3-5f3e-cca2-228fc23e6571',
            'merchant_id' => $merchantId,
            'product_id' => $productId,
            'variant_name' => 'Color',
            'is_active' => 1,
            'created_at' => $timeNow,
        ]);

        \App\Models\Product::query()->create([
            'id' => $productId,
            'merchant_id' => $merchantId,
            'merchant_code' => 'KDENKTF91E24D7',
            'product_name' => $productName,
            'category_id' => 'ef2f2361-1be2-4e3f-bba1-228fd23e5571',
            'category_code' => 'ETKTHZ55CD12101',
            'product_price' => 45.00,
            'stock' => 100,
            'subtract' => 1,
            'brand_id' => null,
            'sku' => 'PWB-ZEUS001',
            'product_description' => 'Zeus Technical PowerBank is most powerful and super fast charging',
            'product_rating' => 0.0,
            'product_condition' => 'NEW',
            'weight' => 500,
            'weight_length' => 'GRAMS',
            'is_pre_order' => 0,

            'dimension_width' => 0,
            'dimension_height' => 0,
            'dimension_length' => 0,

            'pre_order_period' => null,
            'pre_order_length' => null,
            'is_active' => 0,
            'created_at' => $timeNow,
        ]);

        \App\Models\ProductVariant::query()->create([
            'id' => 'de1e2361-1be2-4e3e-dca1-213fc23e6540',
            'product_id' => $productId,
            'merchant_id' => 'ff1f2363-9be3-4f3e-aca3-218fc23e6570',
            'variant_name' => 'Color',
            'sub_variant_name' => 'Red',
            'sort_order' => 0,
            'is_active' => 1,
            'variant_image_id' => null,
            'variant_sku' => 'PWB-ZEUS001',
            'variant_price' => 45.00,
            'variant_stock' => 100,
            'created_at' => $timeNow,
        ]);

        \App\Models\ProductVariant::query()->create([
            'id' => 'de1e2361-1be2-4e3e-dca1-213fc23e6541',
            'product_id' => $productId,
            'merchant_id' => 'ff1f2363-9be3-4f3e-aca3-218fc23e6570',
            'variant_name' => 'Color',
            'sub_variant_name' => 'Green',
            'sort_order' => 1,
            'is_active' => 1,
            'variant_image_id' => null,
            'variant_sku' => 'PWB-ZEUS002',
            'variant_price' => 45.00,
            'variant_stock' => 100,
            'created_at' => $timeNow,
        ]);

        \App\Models\ProductVariant::query()->create([
            'id' => 'de1e2361-1be2-4e3e-dca1-213fc23e6542',
            'product_id' => $productId,
            'merchant_id' => 'ff1f2363-9be3-4f3e-aca3-218fc23e6570',
            'variant_name' => 'Color',
            'sub_variant_name' => 'Blue',
            'sort_order' => 2,
            'is_active' => 1,
            'variant_image_id' => null,
            'variant_sku' => 'PWB-ZEUS003',
            'variant_price' => 45.00,
            'variant_stock' => 100,
            'created_at' => $timeNow,
        ]);

        \App\Models\ProductVariant::query()->create([
            'id' => 'de1e2361-1be2-4e3e-dca1-213fc23e6543',
            'product_id' => $productId,
            'merchant_id' => 'ff1f2363-9be3-4f3e-aca3-218fc23e6570',
            'variant_name' => 'Ampere',
            'sub_variant_name' => '1000 mAh',
            'sort_order' => 0,
            'is_active' => 1,
            'variant_image_id' => null,
            'variant_sku' => 'PWB-ZEUS021',
            'variant_price' => 45.00,
            'variant_stock' => 100,
            'created_at' => $timeNow,
        ]);

        \App\Models\ProductVariant::query()->create([
            'id' => 'de1e2361-1be2-4e3e-dca1-213fc23e6544',
            'product_id' => $productId,
            'merchant_id' => 'ff1f2363-9be3-4f3e-aca3-218fc23e6570',
            'variant_name' => 'Ampere',
            'sub_variant_name' => '2000 mAh',
            'sort_order' => 0,
            'is_active' => 1,
            'variant_image_id' => null,
            'variant_sku' => 'PWB-ZEUS022',
            'variant_price' => 45.00,
            'variant_stock' => 100,
            'created_at' => $timeNow,
        ]);

        \App\Models\Product::query()->where('id', '=', $productId)
            ->update([
                'is_active' => 1
            ]);

        \App\Models\ProductId::query()->where('id', '=', $productId)->delete();
    }
}
