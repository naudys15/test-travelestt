<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PermissionsUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissionuser', function (Blueprint $table) {
            $table->increments('id')->comment('id del permiso de un usuario');
            $table->integer('id')->unsigned()->comment('rol del usuario');
            $table->foreign('id')->references('id')->on('user')->onDelete('cascade');
            $table->integer('id')->unsigned()->comment('rol del usuario');
            $table->foreign('id')->references('id')->on('submodule')->onDelete('cascade');
            $table->integer('id')->unsigned()->comment('rol del usuario');
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
        Schema::dropIfExists('permissionuser');
    }
}
