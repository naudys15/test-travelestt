<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Units extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unitduration', function (Blueprint $table) {
            $table->increments('id')->comment('id de la unidad de medición de la duración');
            $table->string('name', 100)->collation('utf8_general_ci')->comment('nombre');
            $table->string('description', 255)->collation('utf8_general_ci')->comment('descripción');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unitduration');
    }
}
