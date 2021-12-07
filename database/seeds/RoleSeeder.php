<?php

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
