<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RoutesCharacteristics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routecharacteristic', function (Blueprint $table) {
            $table->increments('id')->comment('id de la característica de la ruta');
            $table->integer('id')->unsigned()->comment('id de la ruta');
            $table->foreign('id')->references('id')->on('route')->onDelete('cascade');
            $table->integer('id')->unsigned()->comment('id de característica');
            $table->foreign('id')->references('id')->on('characteristicentity')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routecharacteristic');
    }
}
