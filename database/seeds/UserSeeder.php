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
use App\Models\User;

/**
 * Class UserSeeder.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2021, Odenktools Technology.
 *
 */
class UserSeeder extends Seeder
{
    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function run()
    {
        $password = app('hash')->make('hey1234');
        $timeNow = \Illuminate\Support\Carbon::now();

        $firstName = 'Odenktools';
        $lastName = 'Cituz';

        $user = User::query()->create([
            'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
            'username' => \App\Libraries\NumberLibrary::randomName($firstName . $lastName),
            'email' => 'odenktools@yahoo.com',
            'first_name' => $firstName,
            'last_name' => $lastName,
            'image_id' => null,
            'role_id' => \App\Models\Constants::DEFAULT_ROLE_OWNER,
            'password' => $password,
            'is_active' => 1,
            'join_date' => $timeNow,
            'created_at' => $timeNow,
            'verified_at' => $timeNow,
        ]);

        $user->assignRole('owner');

        $firstName = 'administrator';
        $lastName = 'tokonya';

        $user =  User::query()->create([
            'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
            'username' => \App\Libraries\NumberLibrary::randomName($firstName . $lastName),
            'email' => 'admintokonya@yahoo.com',
            'first_name' => $firstName,
            'last_name' => $lastName,
            'image_id' => null,
            'role_id' => \App\Models\Constants::DEFAULT_ROLE_ADMINISTRATOR,
            'password' => $password,
            'is_active' => 1,
            'join_date' => $timeNow,
            'created_at' => $timeNow,
            'verified_at' => $timeNow,
        ]);

        $user->assignRole('administrator');
    }
}
