<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreatePermissionsTable.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2020, Odenktools Technology.
 */
class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->string('id', 50);
            $table->string('name');
            $table->string('guard_name');

            $table->primary('id');
            $table->timestamps();
        });

        Schema::create('permission_roles', function (Blueprint $table) {
            $table->string('id', 50);
            $table->string('role_id', 50);
            $table->string('permission_id', 50);

            $table->primary('id');

            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('permission_id')->references('id')->on('permissions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('permission_roles');
        Schema::drop('permissions');
    }
}
