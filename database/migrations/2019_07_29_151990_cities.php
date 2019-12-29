<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Cities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('city', function (Blueprint $table) {
            $table->increments('id')->comment('id de la ciudad');
            $table->string('name', 255)->collation('utf8_general_ci')->comment('nombre');
            $table->integer('id')->unsigned()->comment('id del país');
            $table->foreign('id')->references('id')->on('country')->onDelete('cascade');
            $table->integer('id')->unsigned()->comment('id del estado o provincia')->nullable();
            $table->foreign('id')->references('id')->on('province')->onDelete('cascade');
            $table->double('latitude', 16, 6)->comment('latitud')->nullable();
            $table->double('longitude', 16, 6)->comment('longitud')->nullable();
            $table->double('altitude', 16, 4)->comment('altitud')->nullable();
            $table->text('image')->collation('utf8_general_ci')->comment('imagen de ciudad')->nullable();
            $table->boolean('outstanding')->comment('destacado, 0=inactivo, 1=activo')->default(0);
            $table->boolean('top_destination')->comment('top destino, 0=inactivo, 1=activo')->default(0);
            $table->string('slug', 255)->collation('utf8_general_ci')->comment('slug')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('city');
    }
}
