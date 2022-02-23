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
            $table->string('id', 45)->primary();

            $table->string('product_name', 70)
                ->comment('Cannot change product name after create.');

            $table->decimal('product_price', 15, 2)
                ->default(0.0);

            $table->integer('stock')->default(0);

            $table->tinyInteger('subtract')->default(0);

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

            $table->integer('dimension_width')
                ->default(0);

            $table->integer('dimension_height')
                ->default(0);

            $table->integer('dimension_length')
                ->default(0);

            $table->integer('weight');

            $table->string('weight_length', 15)
                ->comment('GRAMS / KILOGRAMS');

            $table->string('merchant_code', 20);

            $table->string('merchant_id', 45);

            $table->string('category_id', 45);

            $table->string('sku', 60)
                ->nullable()
                ->default(null);

            $table->string('brand_id', 45)
                ->nullable()
                ->default(null);

            $table->string('category_code', 20);

            // IMAGES
            $table->string('image_id', 45)
                ->nullable()
                ->default(null);

            // IMAGES 2
            $table->string('image_id2', 45)
                ->nullable()
                ->default(null);

            // IMAGES 3
            $table->string('image_id3', 45)
                ->nullable()
                ->default(null);

            // IMAGES 4
            $table->string('image_id4', 45)
                ->nullable()
                ->default(null);

            $table->timestamps();
            $table->softDeletes();

            $table->index('product_name');
            $table->index('product_price');
            $table->index('product_rating');
            $table->index('is_active');

            $table->foreign('brand_id')->references('id')->on('brands');
            $table->foreign('category_id')->references('id')->on('categories');
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
        Schema::dropIfExists('products');
    }
}
