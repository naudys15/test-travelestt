<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NightSpotsTranslations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nightspottranslation', function (Blueprint $table) {
            $table->increments('id')->comment('id de la traducción del sitio nocturno');
            $table->integer('id')->unsigned()->comment('id del sitio nocturno');
            $table->foreign('id')->references('id')->on('nightspot')->onDelete('cascade');
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
        Schema::dropIfExists('nightspottranslation');
    }
}
