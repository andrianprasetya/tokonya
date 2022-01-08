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
use Spatie\Permission\PermissionRegistrar;
use App\Models\Constants;
use App\Models\Role;
use App\Models\Permission;

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
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $role = Role::create([
                'id' => Constants::DEFAULT_ROLE_OWNER,
                'name' => 'owner',
            ]
        );

        $listRole = Permission::create(['name' => 'list roles']);
        $listRole->assignRole($role);

        $createRole = Permission::create(['name' => 'create roles']);
        $createRole->assignRole($role);

        $deleteRole = Permission::create(['name' => 'delete roles']);
        $deleteRole->assignRole($role);

        $showRole = Permission::create(['name' => 'show roles']);
        $showRole->assignRole($role);

        $role = Role::create([
                'id' => Constants::DEFAULT_ROLE_ADMINISTRATOR,
                'name' => 'administrator',
            ]
        );

        $listRole->assignRole($role);
        $createRole->assignRole($role);
        $showRole->assignRole($role);

        $role = Role::create([
                'id' => Constants::DEFAULT_ROLE_CONTRIBUTOR,
                'name' => 'contributor'
            ]
        );

        $listRole->assignRole($role);
        $showRole->assignRole($role);

        Role::create([
                'id' => Constants::DEFAULT_ROLE_USER,
                'name' => 'user'
            ]
        );
    }
}
