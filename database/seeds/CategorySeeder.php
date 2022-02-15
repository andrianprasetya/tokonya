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

use Illuminate\Database\Seeder;

/**
 * Class CategorySeeder.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2022, Odenktools Technology.
 *
 */
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoryName = 'Fashion';

        $timeNow = \Illuminate\Support\Carbon::now();

        \App\Models\Category::create([
            'id' => 'ef1f2363-9be3-4f3e-bca2-218fc23e6570',
            'category_code' => \App\Libraries\NumberLibrary::randomName($categoryName),
            'category_name' => $categoryName,
            'category_description' => 'Fashion categories',
            'is_active' => 1,
            'image_id' => null,
            'parent_id' => null,
            'created_at' => $timeNow,
        ]);

        $categoryName = 'Electronic';

        \App\Models\Category::create([
            'id' => 'ef2f2361-1be2-4e3f-bba1-228fd23e5571',
            'category_code' => 'ETKTHZ55CD12101',
            'category_name' => $categoryName,
            'category_description' => 'Electronic categories',
            'is_active' => 1,
            'image_id' => null,
            'parent_id' => 'ef1f2363-9be3-4f3e-bca2-218fc23e6570',
            'created_at' => $timeNow,
        ]);


        $categoryName = 'Clothing';

        \App\Models\Category::create([
            'id' => 'ef1f2363-9be3-4f3e-bca2-218fc23e6571',
            'category_code' => \App\Libraries\NumberLibrary::randomName($categoryName),
            'category_name' => $categoryName,
            'category_description' => 'Clothing categories',
            'is_active' => 1,
            'image_id' => null,
            'parent_id' => 'ef1f2363-9be3-4f3e-bca2-218fc23e6570',
            'created_at' => $timeNow,
        ]);

        $categoryName = 'Shoes';

        \App\Models\Category::create([
            'id' => 'ef1f2363-9be3-4f3e-bca2-218fc23e6572',
            'category_code' => \App\Libraries\NumberLibrary::randomName($categoryName),
            'category_name' => $categoryName,
            'category_description' => 'Shoes categories',
            'is_active' => 1,
            'image_id' => null,
            'parent_id' => 'ef1f2363-9be3-4f3e-bca2-218fc23e6570',
            'created_at' => $timeNow,
        ]);
        // ============================================================================ //
        $categoryName = 'Computers';

        \App\Models\Category::create([
            'id' => 'ef1f2363-9be3-4f3e-bca2-218fc23e6561',
            'category_code' => \App\Libraries\NumberLibrary::randomName($categoryName),
            'category_name' => $categoryName,
            'category_description' => 'Computers categories',
            'is_active' => 1,
            'image_id' => null,
            'parent_id' => null,
            'created_at' => $timeNow,
        ]);

        $categoryName = 'Accessories';

        \App\Models\Category::create([
            'id' => 'ef1f2363-9be3-4f3e-bca2-218fc23e6562',
            'category_code' => \App\Libraries\NumberLibrary::randomName($categoryName),
            'category_name' => $categoryName,
            'category_description' => 'Computers categories',
            'is_active' => 1,
            'image_id' => null,
            'parent_id' => 'ef1f2363-9be3-4f3e-bca2-218fc23e6561',
            'created_at' => $timeNow,
        ]);

        $categoryName = 'Data Storage';

        \App\Models\Category::create([
            'id' => 'ef1f2363-9be3-4f3e-bca2-218fc23e6563',
            'category_code' => \App\Libraries\NumberLibrary::randomName($categoryName),
            'category_name' => $categoryName,
            'category_description' => 'Data Storage categories',
            'is_active' => 1,
            'image_id' => null,
            'parent_id' => 'ef1f2363-9be3-4f3e-bca2-218fc23e6561',
            'created_at' => $timeNow,
        ]);

        $categoryName = 'Monitors';

        \App\Models\Category::create([
            'id' => 'ef1f2363-9be3-4f3e-bca2-218fc23e6564',
            'category_code' => \App\Libraries\NumberLibrary::randomName($categoryName),
            'category_name' => $categoryName,
            'category_description' => 'Monitors categories',
            'is_active' => 1,
            'image_id' => null,
            'parent_id' => 'ef1f2363-9be3-4f3e-bca2-218fc23e6561',
            'created_at' => $timeNow,
        ]);

        $categoryName = 'Printers';

        \App\Models\Category::create([
            'id' => 'ef1f2363-9be3-4f3e-bca2-218fc23e6565',
            'category_code' => \App\Libraries\NumberLibrary::randomName($categoryName),
            'category_name' => $categoryName,
            'category_description' => 'Printers categories',
            'is_active' => 1,
            'image_id' => null,
            'parent_id' => 'ef1f2363-9be3-4f3e-bca2-218fc23e6561',
            'created_at' => $timeNow,
        ]);

        $categoryName = 'Scanners';

        \App\Models\Category::create([
            'id' => 'ef1f2363-9be3-4f3e-bca2-218fc23e6566',
            'category_code' => \App\Libraries\NumberLibrary::randomName($categoryName),
            'category_name' => $categoryName,
            'category_description' => 'Scanners categories',
            'is_active' => 1,
            'image_id' => null,
            'parent_id' => 'ef1f2363-9be3-4f3e-bca2-218fc23e6561',
            'created_at' => $timeNow,
        ]);
        // ============================================================================ //

        $categoryName = 'Home Kitchen';

        \App\Models\Category::create([
            'id' => 'ef1f2363-9be3-4f3e-bca2-218fc23e6471',
            'category_code' => \App\Libraries\NumberLibrary::randomName($categoryName),
            'category_name' => $categoryName,
            'category_description' => 'Computers categories',
            'is_active' => 1,
            'image_id' => null,
            'parent_id' => null,
            'created_at' => $timeNow,
        ]);

        $categoryName = 'Bedding';

        \App\Models\Category::create([
            'id' => 'ef1f2363-9be3-4f3e-bca2-218fc23e6472',
            'category_code' => \App\Libraries\NumberLibrary::randomName($categoryName),
            'category_name' => $categoryName,
            'category_description' => 'Bedding categories',
            'is_active' => 1,
            'image_id' => null,
            'parent_id' => 'ef1f2363-9be3-4f3e-bca2-218fc23e6471',
            'created_at' => $timeNow,
        ]);

        $categoryName = 'Bath';

        \App\Models\Category::create([
            'id' => 'ef1f2363-9be3-4f3e-bca2-218fc23e6473',
            'category_code' => \App\Libraries\NumberLibrary::randomName($categoryName),
            'category_name' => $categoryName,
            'category_description' => 'Bath categories',
            'is_active' => 1,
            'image_id' => null,
            'parent_id' => 'ef1f2363-9be3-4f3e-bca2-218fc23e6471',
            'created_at' => $timeNow,
        ]);

        $categoryName = 'Furniture';

        \App\Models\Category::create([
            'id' => 'ef1f2363-9be3-4f3e-bca2-218fc23e6474',
            'category_code' => \App\Libraries\NumberLibrary::randomName($categoryName),
            'category_name' => $categoryName,
            'category_description' => 'Furniture categories',
            'is_active' => 1,
            'image_id' => null,
            'parent_id' => 'ef1f2363-9be3-4f3e-bca2-218fc23e6471',
            'created_at' => $timeNow,
        ]);
    }
}
