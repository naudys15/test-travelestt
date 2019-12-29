<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RoutesTranslations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routetranslation', function (Blueprint $table) {
            $table->increments('id')->comment('id de la traducción de la ruta');
            $table->integer('id')->unsigned()->comment('id de la ruta');
            $table->foreign('id')->references('id')->on('route')->onDelete('cascade');
            $table->integer('id')->unsigned()->comment('id del lenguaje');
            $table->foreign('id')->references('id')->on('language')->onDelete('cascade');
            $table->integer('id')->unsigned()->comment('id del campo suceptible a lenguaje');
            $table->foreign('id')->references('id')->on('languagefield')->onDelete('cascade');
            $table->text('content')->comment('contenido de la traducción');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routetranslation');
    }
}
