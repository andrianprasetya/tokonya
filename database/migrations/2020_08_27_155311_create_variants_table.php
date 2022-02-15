<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variants', function (Blueprint $table) {
            $table->string('id', 40)->primary();
            $table->string('merchant_id', 40);
            $table->string('variant_name', 30);
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();

            $table->foreign('merchant_id')->references('id')->on('merchants');
        });

        Schema::create('variant_has_child', function (Blueprint $table) {
            $table->string('id', 40)->primary();
            $table->string('variant_id', 40);
            $table->string('merchant_id', 40);
            $table->string('sub_variant_name', 30);
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();

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
        Schema::dropIfExists('sub_variants');
        Schema::dropIfExists('variants');
    }
}
