<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NightSpotsCharacteristics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nightspotcharacteristic', function (Blueprint $table) {
            $table->increments('id')->comment('id de la característica del sitio nocturno');
            $table->integer('id')->unsigned()->comment('id del sitio nocturno');
            $table->foreign('id')->references('id')->on('nightspot')->onDelete('cascade');
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
        Schema::dropIfExists('nightspotcharacteristic');
    }
}
