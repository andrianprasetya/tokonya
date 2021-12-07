<?php

use Illuminate\Database\Seeder;

/**
 * Class MerchantSeeder.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2020, Odenktools Technology.
 *
 */
class MerchantSeeder extends Seeder
{
    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function run()
    {
        $password = app('hash')->make('hey1234');

        $merchantName = 'Odenktools Power';

        \App\Models\Merchant::create([
            'id' => 'ff1f2363-9be3-4f3e-aca3-218fc23e6570',
            'merchant_code' => \App\Libraries\NumberLibrary::randomName($merchantName),
            'merchant_name' => $merchantName,
            'email' => 'odenktools@gmail.com',
            'password' => $password,
            'merchant_address' => '4170, Gateway Avenue',
            'merchant_rating' => 0,
            'total_sales' => 0,
            'is_active' => 1,
            'image_id' => null,
            'join_date' => \Carbon\Carbon::now(),
            'created_at' => time(),
            'verified_at' => time(),
        ]);

        $merchantName = 'BalaBala';

        \App\Models\Merchant::create([
            'id' => 'ff1f2363-9be3-4f3e-aca3-218fc23e6571',
            'merchant_code' => \App\Libraries\NumberLibrary::randomName($merchantName),
            'merchant_name' => $merchantName,
            'email' => 'balabala@gmail.com',
            'password' => $password,
            'merchant_address' => '75, Nandewar Street',
            'merchant_rating' => 0,
            'total_sales' => 0,
            'is_active' => 1,
            'image_id' => null,
            'join_date' => \Carbon\Carbon::now(),
            'created_at' => time(),
            'verified_at' => time(),
        ]);

        $merchantName = 'Iku Electronic';

        \App\Models\Merchant::create([
            'id' => 'ff1f2363-9be3-4f3e-aca3-218fc23e6572',
            'merchant_code' => \App\Libraries\NumberLibrary::randomName($merchantName),
            'merchant_name' => $merchantName,
            'email' => 'iku@gmail.com',
            'password' => $password,
            'merchant_address' => '201, Rue de la Mare aux Carats',
            'merchant_rating' => 0,
            'total_sales' => 0,
            'is_active' => 1,
            'image_id' => null,
            'join_date' => \Carbon\Carbon::now(),
            'created_at' => time(),
            'verified_at' => time(),
        ]);
    }
}
