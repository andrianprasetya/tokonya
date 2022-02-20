<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!App::environment('production')) {
            $this->call(OauthSeeder::class);
            $this->call(RoleSeeder::class);
            $this->call(CategorySeeder::class);
            $this->call(UserSeeder::class);
            $this->call(MerchantSeeder::class);
            $this->call(CustomerSeeder::class);
            $this->call(ProductSeeder::class);
        }
    }
}
