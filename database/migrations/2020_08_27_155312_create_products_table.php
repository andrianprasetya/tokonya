<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateProductsTable.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2020, Odenktools Technology.
 */
class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->string('id', 40);

            $table->string('product_name', 150);

            $table->decimal('product_price', 15, 2)
                ->default(0.0);

            $table->text('product_description')
                ->default(null)
                ->nullable();

            $table->tinyInteger('product_rating')
                ->default(0);

            $table->tinyInteger('is_active')
                ->default(1)
                ->comment('1 = product is active?');

            $table->string('category_code', 20);

            $table->string('category_id', 50);

            $table->string('merchant_code', 20);

            $table->string('merchant_id', 50);

            // IMAGES
            $table->string('image_id', 40)
                ->nullable()
                ->default(null);

            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');

            $table->index('product_name');
            $table->index('product_price');
            $table->index('product_rating');
            $table->index('is_active');

            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('merchant_id')->references('id')->on('merchants');
        });

        Schema::create('product_reviews', function (Blueprint $table) {
            $table->string('id', 40);
            $table->string('product_id', 40);
            $table->string('customer_id', 40);
            $table->string('customer_name', 70);
            $table->string('product_name', 150);
            $table->tinyInteger('rating')
                ->default(1);
            $table->text('review');

            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');

            $table->index('customer_name');
            $table->index('product_name');

            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_reviews');
        Schema::dropIfExists('products');
    }
}
