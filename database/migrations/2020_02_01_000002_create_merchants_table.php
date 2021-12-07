<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateMerchantsTable.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2020, Odenktools Technology.
 */
class CreateMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchants', function (Blueprint $table) {

            $table->string('id', 50);
            $table->string('email', 80)->unique();

            $table->string('merchant_code', 20)->unique();
            $table->string('merchant_name', 100)->unique();

            $table->string('password');

            $table->string('image_id', 50)
                ->default(null)
                ->nullable()
                ->comment('Avatar Images');

            $table->tinyInteger('is_active')
                ->default(1)
                ->comment('0 = Not Active, 1 = Active');

            $table->rememberToken();
            $table->date('join_date');
            $table->bigInteger('verified_at')->nullable();
            $table->bigInteger("created_at");
            $table->bigInteger("updated_at")->nullable();
            $table->bigInteger("deleted_at")->nullable();

            $table->index('email');
            $table->index('merchant_code');
            $table->index('merchant_name');

            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('merchants');
    }
}
