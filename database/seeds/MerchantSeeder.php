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
            'merchant_code' => 'KDENKTF91E24D7',
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
            'merchant_code' => 'DD43TTF91E24F3',
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
