<?php

use Illuminate\Database\Seeder;

/**
 * Class CustomerSeeder.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2020, Odenktools Technology.
 *
 */
class CustomerSeeder extends Seeder
{
    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function run()
    {
        $password = app('hash')->make('hey1234');

        $customerName = 'Alamsyah';

        \App\Models\Customer::create([
            'id' => 'ee1f2362-9bf3-333e-aca3-318fc23f6510',
            'customer_code' => \App\Libraries\NumberLibrary::randomName($customerName),
            'customer_name' => $customerName,
            'email' => 'alamsyah@gmail.com',
            'password' => $password,
            'is_active' => 1,
            'image_id' => null,
            'join_date' => \Carbon\Carbon::now(),
            'created_at' => time(),
            'verified_at' => time(),
        ]);

        $customerName = 'Suparno Alex';

        \App\Models\Customer::create([
            'id' => 'ee1f2362-9bf3-333e-aca3-318fc23f6511',
            'customer_code' => \App\Libraries\NumberLibrary::randomName($customerName),
            'customer_name' => $customerName,
            'email' => 'suparnoalex@gmail.com',
            'password' => $password,
            'is_active' => 1,
            'image_id' => null,
            'join_date' => \Carbon\Carbon::now(),
            'created_at' => time(),
            'verified_at' => time(),
        ]);
    }
}
