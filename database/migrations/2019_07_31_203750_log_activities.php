<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LogActivities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logactivity', function (Blueprint $table) {
            $table->increments('id')->comment('id de la operación');
            $table->string('description', 255)->collation('utf8_general_ci')->comment('actividad realizada');
            $table->dateTime('date')->comment('fecha');
            $table->integer('id')->unsigned()->comment('id del cliente');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logactivity');
    }
}
