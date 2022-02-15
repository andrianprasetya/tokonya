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
class VariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timeNow = \Illuminate\Support\Carbon::now();

        \App\Models\Variant::query()->create([
            'id' => 'ff1d2363-9be3-5f3e-cca2-228fc23e6571',
            'merchant_id' => 'ff1f2363-9be3-4f3e-aca3-218fc23e6570',
            'variant_name' => 'Color',
            'is_active' => 1,
            'created_at' => $timeNow,
        ]);

        \App\Models\VariantHasChild::query()->create([
            'id' => 'ef1f2362-9be3-4f3e-bca2-218fc23e6570',
            'variant_id' => 'ff1d2363-9be3-5f3e-cca2-228fc23e6571',
            'merchant_id' => 'ff1f2363-9be3-4f3e-aca3-218fc23e6570',
            'sub_variant_name' => 'Red',
            'sort_order' => 0,
            'is_active' => 1,
            'created_at' => $timeNow,
        ]);

        \App\Models\VariantHasChild::query()->create([
            'id' => 'ef1f2363-9be3-4f3e-bca2-218fc23e6570',
            'variant_id' => 'ff1d2363-9be3-5f3e-cca2-228fc23e6571',
            'merchant_id' => 'ff1f2363-9be3-4f3e-aca3-218fc23e6570',
            'sub_variant_name' => 'Green',
            'sort_order' => 1,
            'is_active' => 1,
            'created_at' => $timeNow,
        ]);

        \App\Models\VariantHasChild::query()->create([
            'id' => 'ef1f2364-9be3-4f3e-bca2-218fc23e6570',
            'variant_id' => 'ff1d2363-9be3-5f3e-cca2-228fc23e6571',
            'merchant_id' => 'ff1f2363-9be3-4f3e-aca3-218fc23e6570',
            'sub_variant_name' => 'Blue',
            'sort_order' => 2,
            'is_active' => 1,
            'created_at' => $timeNow,
        ]);
    }
}
