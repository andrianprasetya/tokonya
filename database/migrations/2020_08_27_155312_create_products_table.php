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

            $table->string('product_name', 150)
                ->nullable()
                ->default(null);

            $table->decimal('product_price', 15, 2)
                ->default(0.0);

            $table->text('product_description')
                ->default(null)
                ->nullable();

            $table->tinyInteger('is_active')
                ->default(1)
                ->comment('1 = jika product aktif');

            $table->string('category_code', 20);

            $table->string('category_id', 50);

            $table->string('merchant_code', 20);

            $table->string('merchant_id', 50);

            // IMAGES
            $table->string('image_id', 40)
                ->nullable()
                ->default(null);

            $table->bigInteger("created_at");
            $table->bigInteger("updated_at")->nullable();
            $table->bigInteger("deleted_at")->nullable();

            $table->primary('id');

            $table->index('product_name');
            $table->index('product_price');
            $table->index('is_active');

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
