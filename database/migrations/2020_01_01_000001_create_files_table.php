<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateImagesTable.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2020, Odenktools Technology.
 */
class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->string('id', 45);
            $table->string('module_type')
                ->comment('only : super, customers, merchants, products, other');
            $table->string('extension', 30);
            $table->text('path');
            $table->text('file_url');

            $table->bigInteger("created_at");
            $table->bigInteger("updated_at")->nullable();

            $table->primary(['id']);
            $table->index('module_type');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('files');
    }
}
