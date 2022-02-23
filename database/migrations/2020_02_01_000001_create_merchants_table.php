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

            $table->string('id', 45);
            $table->string('email', 80)->unique();

            $table->string('merchant_code', 20)->unique();
            $table->string('merchant_name', 100)->unique();

            $table->string('merchant_slogan', 50)
                ->default(null)
                ->nullable();

            $table->string('merchant_description', 120)
                ->default(null)
                ->nullable();

            $table->string('password');

            $table->text('merchant_address')
                ->default(null)
                ->comment('Only address, no city and others')
                ->nullable();

            $table->text('merchant_city')
                ->default(null)
                ->comment('City or districts')
                ->nullable();

            $table->text('zip_code')
                ->default(null)
                ->nullable();

            $table->string('image_id', 45)
                ->default(null)
                ->nullable()
                ->comment('Avatar Images');

            $table->string('passport_identity_id', 45)
                ->default(null)
                ->nullable()
                ->comment('Drive licenses/Passport Images');

            $table->tinyInteger('merchant_rating')
                ->default(0);

            $table->bigInteger('total_sales')
                ->default(0)
                ->comment('Total sales');

            $table->tinyInteger('is_active')
                ->default(1)
                ->comment('0 = Not Active, 1 = Active');

            $table->tinyInteger('profile_rating')
                ->default(1)
                ->comment('Complete profile rating');

            $table->rememberToken();
            $table->date('join_date');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('email');
            $table->index('merchant_rating');
            $table->index('total_sales');
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
