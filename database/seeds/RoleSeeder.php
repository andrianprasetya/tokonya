<?php

/**
 * Copyright 2021 Odenktools Technology Open Source Project
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
 * Class RoleSeeder.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2020, Odenktools Technology.
 *
 */
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Role::create([
            'id' => \App\Models\Constants::DEFAULT_ROLE_OWNER,
            'role_name' => 'Owner',
            'slug' => 'owner',
            'role_desc' => 'Owner',
            'is_active' => 1,
            'is_default' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        \App\Models\Role::create([
            'id' => \App\Models\Constants::DEFAULT_ROLE_ADMINISTRATOR,
            'role_name' => 'Administrator',
            'slug' => 'administrator',
            'role_desc' => 'administrator',
            'is_active' => 1,
            'is_default' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        \App\Models\Role::create([
            'id' => \App\Models\Constants::DEFAULT_ROLE_CONTRIBUTOR,
            'role_name' => 'Contributor',
            'slug' => 'contributor',
            'role_desc' => 'contributor',
            'is_active' => 1,
            'is_default' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        \App\Models\Role::create([
            'id' => \App\Models\Constants::DEFAULT_ROLE_USER,
            'role_name' => 'User',
            'slug' => 'user',
            'role_desc' => 'user',
            'is_active' => 1,
            'is_default' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }
}
