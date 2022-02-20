<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateBrandsTable.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2022, Odenktools Technology.
 */
class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->string('id', 40)
                ->primary();
            $table->string('merchant_id', 40);
            $table->string('brand_code', 20);
            $table->string('brand_name', 70);

            $table->text('brand_description')
                ->nullable();

            $table->string('image_id')->nullable();

            $table->tinyInteger('is_active')->default(1);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('image_id')->references('id')->on('files');
            $table->foreign('merchant_id')->references('id')->on('merchants');

            $table->index('brand_code');
            $table->index('brand_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brands');
    }
}
