<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Routes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route', function (Blueprint $table) {
            $table->increments('id')->comment('id de la ruta');
            $table->string('slug', 150)->collation('utf8_general_ci')->comment('slug');
            // $table->string('type', 62)->comment('tipo');
            // $table->string('level', 62)->comment('nivel');
            $table->text('slopes')->comment('pendientes');
            $table->float('latitude_start', 8, 6)->comment('latitud de inicio');
            $table->float('longitude_start', 8, 6)->comment('longitud de inicio');
            $table->float('latitude_end', 8, 6)->comment('latitud de fin');
            $table->float('longitude_end', 8, 6)->comment('longitud de fin');
            $table->float('duration_value', 5, 2)->comment('duración de la ruta')->nullable();
            $table->integer('id')->unsigned()->comment('id de la unidad de medición de la duración')->nullable();
            $table->foreign('id')->references('id')->on('unitduration')->onDelete('cascade');
            $table->integer('id')->unsigned()->comment('ciudad');
            $table->foreign('id')->references('id')->on('city')->onDelete('cascade');
            $table->text('media')->collation('utf8_general_ci')->comment('imagenes')->nullable();
            $table->integer('status')->comment('status, 0=inactivo, 1=activo')->default(1);
            $table->boolean('outstanding')->comment('destacado, 0=inactivo, 1=activo')->default(0);
            $table->timestamp('created')->nullable();
            $table->string('addedby', 62)->collation('utf8_general_ci')->comment('usuario que creó');
            $table->timestamp('updated')->nullable();
            $table->string('updateby', 62)->collation('utf8_general_ci')->comment('usuario que actualizó');
            $table->softDeletes('deleted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('route');
    }
}
