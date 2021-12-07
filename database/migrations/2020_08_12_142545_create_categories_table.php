<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateCategoriesTable.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2020, Odenktools Technology.
 */
class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->string('id', 40)
                ->primary();
            $table->string('category_code', 20);
            $table->string('category_name', 70);

            $table->text('category_description')
                ->nullable();

            $table->string('parent_id')
                ->nullable();

            $table->string('image_id')
                ->nullable();

            $table->tinyInteger('is_active')
                ->default(1);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('image_id')->references('id')->on('files');

            $table->index('category_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
