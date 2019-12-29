<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CharacteristicsCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('characteristiccategory', function (Blueprint $table) {
            $table->increments('id')->comment('id de la categoría');
            $table->string('name', 100)->collation('utf8_general_ci')->comment('nombre');
            $table->string('description', 255)->collation('utf8_general_ci')->comment('descripción');
            $table->string('render', 20)->collation('utf8_general_ci')->comment('etiqueta HTML para renderizar');
            $table->integer('id')->unsigned()->comment('id de la entidad');
            $table->foreign('id')->references('id')->on('entity')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('characteristiccategory');
    }
}
