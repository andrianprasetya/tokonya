<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateRolesTable.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2020, Odenktools Technology.
 */
class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->string('id', 50);
            $table->string('role_name', 150);
            $table->string('slug', 150)->unique();
            $table->text('role_desc')->nullable();
            $table->tinyInteger('is_active')
                ->default(1)
                ->comment('0 = Not Active, 1 = Active');
            $table->tinyInteger('is_default')
                ->default(1)
                ->comment('1 = Cannot delete by program');

            $table->bigInteger("created_at");
            $table->bigInteger("updated_at")->nullable();

            $table->primary('id');
            $table->index('role_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('roles');
    }
}
