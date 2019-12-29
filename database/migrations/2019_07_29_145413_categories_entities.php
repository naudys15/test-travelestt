<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CategoriesEntities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('categoryentity', function (Blueprint $table) {
        //     $table->increments('caen_id')->comment('id de la categoría en la entidad');
        //     $table->integer('id')->unsigned()->comment('id de la entidad');
        //     $table->foreign('id')->references('id')->on('entity')->onDelete('cascade');
        //     $table->integer('id')->unsigned()->comment('id de categoría');
        //     $table->foreign('id')->references('id')->on('characteristiccategory')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('categoryentity');
    }
}
