<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CoastsCharacteristics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coastcharacteristic', function (Blueprint $table) {
            $table->increments('id')->comment('id de la característica de la playa o cala');
            $table->integer('id')->unsigned()->comment('id de la playa o cala');
            $table->foreign('id')->references('id')->on('coast')->onDelete('cascade');
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
        Schema::dropIfExists('coastcharacteristic');
    }
}
