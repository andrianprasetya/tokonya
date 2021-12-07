<?php

use Illuminate\Database\Seeder;

/**
 * Class CategorySeeder.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2020, Odenktools Technology.
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

        \App\Models\Category::create([
            'id' => 'ef1f2363-9be3-4f3e-bca2-218fc23e6570',
            'category_code' => \App\Libraries\NumberLibrary::randomName($categoryName),
            'category_name' => $categoryName,
            'category_description' => 'Fashion categories',
            'is_active' => 1,
            'image_id' => null,
            'parent_id' => null,
            'created_at' => time(),
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
            'created_at' => time(),
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
            'created_at' => time(),
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
            'created_at' => time(),
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
            'created_at' => time(),
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
            'created_at' => time(),
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
            'created_at' => time(),
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
            'created_at' => time(),
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
            'created_at' => time(),
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
            'created_at' => time(),
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
            'created_at' => time(),
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
            'created_at' => time(),
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
            'created_at' => time(),
        ]);
    }
}
