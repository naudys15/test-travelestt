<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FestivalsTranslations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('festivaltranslation', function (Blueprint $table) {
            $table->increments('id')->comment('id de la traducción del festival');
            $table->integer('id')->unsigned()->comment('id del festival');
            $table->foreign('id')->references('id')->on('festival')->onDelete('cascade');
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
        Schema::dropIfExists('festivaltranslation');
    }
}
