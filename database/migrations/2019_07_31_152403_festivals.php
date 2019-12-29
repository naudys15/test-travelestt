<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Festivals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('festival', function (Blueprint $table) {
            $table->increments('id')->comment('id del festival');
            $table->string('slug', 150)->collation('utf8_general_ci')->comment('slug');
            $table->float('latitude', 8, 6)->comment('latitud');
            $table->float('longitude', 8, 6)->comment('longitud');
            $table->float('duration_value', 5, 2)->comment('duración del festival')->nullable();
            $table->integer('id')->unsigned()->comment('id de la unidad de medición de la duración')->nullable();
            $table->foreign('id')->references('id')->on('unitduration')->onDelete('cascade');
            $table->integer('id')->unsigned()->comment('ciudad');
            $table->foreign('id')->references('id')->on('city')->onDelete('cascade');
            $table->text('media')->collation('utf8_general_ci')->comment('imagenes')->nullable();
            $table->integer('status')->comment('status, 0=inactivo, 1=activo')->default(1);
            $table->boolean('outstanding')->comment('Destacado')->default(0);
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
        Schema::dropIfExists('festival');
    }
}
