<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RangeSubModules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rangesubmodule', function (Blueprint $table) {
            $table->increments('id')->comment('id del rango de visibilidad en el submódulo');
            $table->string('name', 100)->collation('utf8_general_ci')->comment('nombre');
            $table->string('description', 255)->collation('utf8_general_ci')->comment('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rangesubmodule');
    }
}
