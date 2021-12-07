<?php

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
                'id' => 2,
                'user_id' => null,
                'name' => 'ClientCredentials Grant Client',
                'secret' => 'hTu54Y9PAgyBCi1EUw45FSafL8hyLUklYXe08OAW',
                'provider' => null,
                'redirect' => '',
                'personal_access_client' => 'f',
                'password_client' => 'f',
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
