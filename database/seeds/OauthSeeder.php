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
use Illuminate\Support\Facades\DB;

class OauthSeeder extends Seeder
{
    public function run()
    {
        DB::table('oauth_clients')->insert(
            [
                'id' => 1,
                'user_id' => null,
                'name' => 'Password Grant Client',
                'secret' => 'imJrfnJAHPtCreZE4SriBxLZEjDfkFraKLB0aJMG',
                'provider' => 'users',
                'redirect' => 'http://localhost',
                'personal_access_client' => 'f',
                'password_client' => 't',
                'revoked' => 'f',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('oauth_clients')->insert(
            [
                'id' => 3,
                'user_id' => null,
                'name' => 'Merchant Client',
                'secret' => 'imJrfnJAHPtCre1E4SriBxLZEjDfkFraKLB0aJMi',
                'provider' => 'merchants',
                'redirect' => 'http://localhost',
                'personal_access_client' => 'f',
                'password_client' => 't',
                'revoked' => 'f',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('oauth_clients')->insert(
            [
                'id' => 4,
                'user_id' => null,
                'name' => 'Customer Client',
                'secret' => 'imJrfnJAHPtCre1E4SriBxLZfjDfkFraKLB0aJMi',
                'provider' => 'customers',
                'redirect' => 'http://localhost',
                'personal_access_client' => 'f',
                'password_client' => 't',
                'revoked' => 'f',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
