<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateUsersTable.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2020, Odenktools Technology.
 */
class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {

            $table->string('id', 45);
            $table->string('role_id', 45);
            $table->string('email', 80)->unique();
            $table->string('username', 70)->unique();

            $table->string('first_name', 100)
                ->default(null)
                ->nullable();

            $table->string('last_name', 100)
                ->default(null)
                ->nullable();

            $table->string('password');

            $table->string('image_id', 45)
                ->default(null)
                ->nullable()
                ->comment('Avatar Images');

            $table->tinyInteger('is_active')
                ->default(1)
                ->comment('0 = Not Active, 1 = Active');

            $table->rememberToken();
            $table->date('join_date');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('email');

            $table->primary('id');
            $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
