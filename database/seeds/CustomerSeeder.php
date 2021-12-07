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
        $timeNow = \Illuminate\Support\Carbon::now();

        $customerName = 'Alexandria';

        \App\Models\Customer::query()->create([
            'id' => 'ee1f2362-9bf3-333e-aca3-318fc23f6510',
            'customer_code' => \App\Libraries\NumberLibrary::randomName($customerName),
            'customer_name' => $customerName,
            'email' => 'alexandria@gmail.com',
            'password' => $password,
            'gender' => 'female',
            'customer_address' => null,
            'is_active' => 1,
            'image_id' => null,
            'join_date' => $timeNow,
            'created_at' => $timeNow,
            'verified_at' => $timeNow,
        ]);

        $customerName = 'John Doe';

        \App\Models\Customer::query()->create([
            'id' => 'ee1f2362-9bf3-333e-aca3-318fc23f6511',
            'customer_code' => \App\Libraries\NumberLibrary::randomName($customerName),
            'customer_name' => $customerName,
            'email' => 'johndoe@gmail.com',
            'password' => $password,
            'is_active' => 1,
            'gender' => 'male',
            'customer_address' => null,
            'image_id' => null,
            'join_date' => $timeNow,
            'created_at' => $timeNow,
            'verified_at' => $timeNow,
        ]);
    }
}
