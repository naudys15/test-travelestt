<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Provinces extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('province', function (Blueprint $table) {
            $table->increments('id')->comment('id de la provincia o estado');
            $table->string('name', 100)->collation('utf8_general_ci')->comment('nombre');
            $table->integer('id')->unsigned()->comment('id del país');
            $table->foreign('id')->references('id')->on('country')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('province');
    }
}
