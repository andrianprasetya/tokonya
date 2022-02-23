<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductIdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_ids', function (Blueprint $table) {
            $table->string('id', 45)->primary();
            $table->string('merchant_id', 45);
            $table->string('product_name', 70)
                ->comment('Cannot change product name after create.');
            // IMAGES
            $table->string('image_id', 45);
            $table->foreign('merchant_id')->references('id')->on('merchants');
            $table->index('product_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_ids');
    }
}
