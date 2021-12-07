<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateCustomersTable.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2020, Odenktools Technology.
 */
class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {

            $table->string('id', 50);
            $table->string('email', 80)->unique();

            $table->string('customer_code', 20)->unique();
            $table->string('customer_name', 70)->unique();

            $table->string('password');

            $table->string('gender', 10)
                ->default(null)
                ->comment('male/female/other');

            $table->text('customer_address')
                ->default(null)
                ->nullable();

            $table->string('image_id', 50)
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
            $table->index('customer_code');
            $table->index('customer_name');

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
        Schema::drop('customers');
    }
}
