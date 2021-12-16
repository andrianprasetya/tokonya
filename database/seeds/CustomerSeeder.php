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
            'merchant_id' => null,
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
            'merchant_id' => null,
            'image_id' => null,
            'join_date' => $timeNow,
            'created_at' => $timeNow,
            'verified_at' => $timeNow,
        ]);
    }
}
