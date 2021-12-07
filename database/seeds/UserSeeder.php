<?php

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

        User::query()->create([
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
    }
}
