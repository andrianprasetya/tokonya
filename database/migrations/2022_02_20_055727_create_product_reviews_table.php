<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
    }
}
