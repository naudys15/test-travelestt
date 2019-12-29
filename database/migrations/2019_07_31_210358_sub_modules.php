<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SubModules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submodule', function (Blueprint $table) {
            $table->increments('id')->comment('id del submódulo');
            $table->string('name', 100)->collation('utf8_general_ci')->comment('nombre');
            $table->string('description', 255)->collation('utf8_general_ci')->comment('description');
            $table->integer('id')->unsigned()->comment('id de la estación');
            $table->foreign('id')->references('id')->on('module')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('submodule');
    }
}
