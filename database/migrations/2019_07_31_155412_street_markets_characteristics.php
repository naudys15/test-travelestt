<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StreetMarketsCharacteristics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('streetmarketcharacteristic', function (Blueprint $table) {
            $table->increments('id')->comment('id de la característica del mercadillo');
            $table->integer('id')->unsigned()->comment('id del mercadillo');
            $table->foreign('id')->references('id')->on('streetmarket')->onDelete('cascade');
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
        Schema::dropIfExists('streetmarketcharacteristic');
    }
}
