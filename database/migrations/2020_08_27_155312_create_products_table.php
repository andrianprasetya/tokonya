<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateProductsTable.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2022, Odenktools Technology.
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
            $table->string('id', 40)->primary();

            $table->string('product_name', 70);

            $table->decimal('product_price', 15, 2)
                ->default(0.0);

            $table->integer('stock')
                ->default(0);

            $table->tinyInteger('subtract')
                ->default(0);

            $table->text('product_description')
                ->default(null)
                ->nullable();

            $table->tinyInteger('product_rating')
                ->default(0);

            $table->string('product_condition', 10)
                ->default('NEW')
                ->comment('NEW | SECOND');

            $table->tinyInteger('is_active')
                ->default(1)
                ->comment('1 = product is active?');

            $table->tinyInteger('is_pre_order')
                ->default(0)
                ->comment('1 = product must pre-order?');

            $table->string('pre_order_period', 10)
                ->default(null)
                ->nullable()
                ->comment('DAY / WEEK');

            $table->integer('pre_order_length')
                ->nullable()
                ->comment('');

            $table->string('merchant_code', 20);

            $table->string('merchant_id', 40);

            $table->string('category_id', 40);

            $table->string('category_code', 20);

            // IMAGES
            $table->string('image_id', 40)
                ->nullable()
                ->default(null);

            // IMAGES
            $table->string('image_id2', 40)
                ->nullable()
                ->default(null);

            // IMAGES
            $table->string('image_id3', 40)
                ->nullable()
                ->default(null);

            // IMAGES
            $table->string('image_id4', 40)
                ->nullable()
                ->default(null);

            $table->timestamps();
            $table->softDeletes();

            $table->index('product_name');
            $table->index('product_price');
            $table->index('product_rating');
            $table->index('is_active');

            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('merchant_id')->references('id')->on('merchants');
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->string('id', 40)->primary();
            $table->string('merchant_id', 40);
            $table->string('product_id', 40);
            $table->string('variant_id', 40);
            $table->string('variant_child_id', 40);
            // IMAGES.
            $table->string('variant_image_id', 40)
                ->nullable()
                ->default(null);
            // PRICE PER VARIANT.
            $table->decimal('variant_price', 15, 2)
                ->default(0.0);
            $table->integer('variant_stock')->default(0);
            $table->tinyInteger('variant_subtract')->default(0);

            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('variant_id')->references('id')->on('variants');
            $table->foreign('variant_child_id')->references('id')->on('variant_has_child');
            $table->foreign('merchant_id')->references('id')->on('merchants');
        });

        Schema::create('product_reviews', function (Blueprint $table) {
            $table->string('id', 40)->primary();
            $table->string('merchant_id', 40);
            $table->string('product_id', 40);
            $table->string('customer_id', 40);
            $table->string('customer_name', 70);
            $table->string('product_name', 150);
            $table->tinyInteger('rating')
                ->default(1);
            $table->text('review');

            $table->timestamps();
            $table->softDeletes();

            $table->index('customer_name');
            $table->index('product_name');

            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('merchant_id')->references('id')->on('merchants');
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
