<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FestivalsCharacteristics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('festivalcharacteristic', function (Blueprint $table) {
            $table->increments('id')->comment('id de la característica del festival');
            $table->integer('id')->unsigned()->comment('id del festival');
            $table->foreign('id')->references('id')->on('festival')->onDelete('cascade');
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
        Schema::dropIfExists('festivalcharacteristic');
    }
}
