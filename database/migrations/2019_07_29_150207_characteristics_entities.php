<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CharacteristicsEntities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('characteristicentity', function (Blueprint $table) {
            $table->increments('id')->comment('id de la característica');
            $table->integer('id')->unsigned()->comment('id de categoría');
            $table->foreign('id')->references('id')->on('characteristiccategory')->onDelete('cascade');
            $table->string('name', 255)->collation('utf8_general_ci')->comment('nombre');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('characteristicentity');
    }
}
