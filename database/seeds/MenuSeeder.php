<?php

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

/**
 * Class MenuSeeder
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2020, Odenktools Technology.
 *
 */
class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws Exception
     */
    public function run()
    {
        // ================ START DEFAULT ROOT MENU ================ //

        //Default admin sidebar
        $adminDefault = \App\Models\Menu::create([
            'id' => \App\Models\Constants::DEFAULT_SIDEBAR,
            'parent_id' => '0',
            'menu_title' => 'Admin Menu',
            'slug' => 'admin',
            'icon' => 'fa fa-dashboard',
            'menu_order' => 0,
            'is_active' => 1,
            'is_default' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ])->id;

        $dashboardMenu = \App\Models\Menu::create([
            'id' => Uuid::uuid4()->toString(),
            'parent_id' => $adminDefault,
            'menu_title' => 'Dashboard',
            'slug' => 'dashboard',
            'icon' => 'fa fa-dashboard',
            'url' => 'dashboard',
            'menu_order' => 0,
            'is_default' => 1,
            'is_active' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ])->id;

        $siteMenu = \App\Models\Menu::create([
            'id' => Uuid::uuid4()->toString(),
            'parent_id' => $adminDefault,
            'menu_title' => 'Sites management',
            'slug' => 'sites-management',
            'icon' => 'fa fa-dashboard',
            'menu_order' => 1,
            'is_default' => 1,
            'is_active' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ])->id;

        \App\Models\Menu::create([
            'id' => Uuid::uuid4()->toString(),
            'parent_id' => $siteMenu,
            'menu_title' => 'Roles',
            'slug' => 'roles',
            'url' => 'roles',
            'icon' => 'fa fa-user',
            'menu_order' => 0,
            'is_active' => 1,
            'is_default' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ])->id;

        \App\Models\Menu::create([
            'id' => Uuid::uuid4()->toString(),
            'parent_id' => $siteMenu,
            'menu_title' => 'Users',
            'slug' => 'users',
            'url' => 'users',
            'icon' => 'fa fa-user',
            'menu_order' => 1,
            'is_active' => 1,
            'is_default' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ])->id;

        \App\Models\Menu::create([
            'id' => Uuid::uuid4()->toString(),
            'parent_id' => $siteMenu,
            'menu_title' => 'Menu',
            'slug' => 'sidebars',
            'url' => 'sidebars',
            'icon' => 'fa fa-user',
            'menu_order' => 2,
            'is_active' => 1,
            'is_default' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ])->id;

        // ================ END DEFAULT ROOT MENU ================ //


        $menus = \App\Models\Menu::select()
            ->whereNotNull('slug')
            ->get();

        foreach ($menus as $menu) {
            // Add menu to owner
            \App\Models\MenuRole::create([
                'id' => Uuid::uuid4()->toString(),
                'role_id' => \App\Models\Constants::DEFAULT_ROLE_OWNER,
                'menu_id' => $menu->id,
                'is_enabled' => 1,
            ]);
            \App\Models\MenuRole::create([
                'id' => Uuid::uuid4()->toString(),
                'role_id' => \App\Models\Constants::DEFAULT_ROLE_ADMINISTRATOR,
                'menu_id' => $menu->id,
                'is_enabled' => 1
            ]);
        }
    }
}