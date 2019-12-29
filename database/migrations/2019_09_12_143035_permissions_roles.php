<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PermissionsRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissionrole', function (Blueprint $table) {
            $table->increments('id')->comment('id del permiso de un modulo');
            $table->integer('id')->unsigned()->comment('id del rol');
            $table->foreign('id')->references('id')->on('role')->onDelete('cascade');
            $table->integer('id')->unsigned()->comment('id del submodulo');
            $table->foreign('id')->references('id')->on('submodule')->onDelete('cascade');
            $table->integer('id')->unsigned()->comment('id del rango');
            $table->foreign('id')->references('id')->on('rangesubmodule')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissionrole');
    }
}
