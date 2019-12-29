<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StreetMarketsValoration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('streetmarketvaloration', function (Blueprint $table) {
            $table->increments('id')->comment('id de la valoracion del mercadillo');
            $table->integer('id')->unsigned()->comment('id del mercadillo');
            $table->foreign('id')->references('id')->on('streetmarket');
            $table->integer('id')->unsigned()->comment('id del usuario');
            $table->foreign('id')->references('id')->on('user');
            $table->char('title', 100)->collation('utf8_general_ci')->comment('titulo de la valoracion');
            $table->text('content')->collation('utf8_general_ci')->comment('contenido de la valoracion');
            $table->integer('rating')->unsigned()->comment('rating de la valoracion');
            $table->boolean('status')->comment('estatus de la valoracion')->default(0);
            $table->dateTime('created')->comment('fecha de creación');
            $table->dateTime('updated')->comment('fecha de actualización');
            $table->softDeletes('deleted')->comment('fecha de eliminación');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('streetmarketvaloration');
    }
}
