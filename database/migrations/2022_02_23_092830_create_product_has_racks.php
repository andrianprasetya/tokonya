<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductHasRacks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_has_racks', function (Blueprint $table) {
            $table->string('id', 45)->primary();
            $table->string('rack_id', 45);

            $table->string('rack_name', 50)
                ->comment('Cannot change rack name after create.');
            $table->string('product_name', 70);

            $table->string('product_id', 45);

            $table->string('merchant_id', 45);

            $table->timestamps();

            $table->foreign('rack_id')->references('id')->on('racks');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('merchant_id')->references('id')->on('merchants');

            $table->index('rack_name');
            $table->index('product_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_has_racks');
    }
}
