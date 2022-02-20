<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->string('id', 40)->primary();
            $table->string('merchant_id', 40);
            $table->string('product_id', 40);
            $table->string('variant_id', 40);

            $table->string('sub_variant_name', 30);
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('is_active')->default(1);

            $table->string('variant_sku', 60)
                ->nullable()
                ->default(null);
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
        Schema::dropIfExists('product_variants');
    }
}
