<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Stations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('station', function (Blueprint $table) {
            $table->increments('id')->comment('id de la estación');
            $table->float('latitude', 8, 6)->comment('latitud');
            $table->float('longitude', 8, 6)->comment('longitud');
            $table->integer('id')->unsigned()->comment('id de la ruta');
            $table->foreign('id')->references('id')->on('route')->onDelete('cascade');
            $table->integer('id')->unsigned()->comment('ciudad');
            $table->foreign('id')->references('id')->on('city')->onDelete('cascade');
            $table->timestamp('created')->nullable();
            $table->timestamp('updated')->nullable();
            $table->softDeletes('deleted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('station');
    }
}
