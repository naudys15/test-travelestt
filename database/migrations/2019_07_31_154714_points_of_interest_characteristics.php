<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PointsOfInterestCharacteristics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pointofinterestcharacteristic', function (Blueprint $table) {
            $table->increments('id')->comment('id de la característica del punto de interés');
            $table->integer('id')->unsigned()->comment('id del punto de interés');
            $table->foreign('id')->references('id')->on('pointofinterest')->onDelete('cascade');
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
        Schema::dropIfExists('pointofinterestcharacteristic');
    }
}
