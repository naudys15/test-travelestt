<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Users extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id')->comment('id del usuario');
            $table->string('firstname', 255)->collation('utf8_general_ci')->comment('nombre');
            $table->string('lastname', 255)->collation('utf8_general_ci')->comment('apellido');
            $table->integer('id')->unsigned()->comment('ciudad');
            $table->foreign('id')->references('id')->on('city')->onDelete('cascade');
            $table->string('phonenumber', 25)->collation('utf8_general_ci')->comment('teléfono');
            $table->string('email', 50)->collation('utf8_general_ci')->comment('email');
            $table->text('image')->collation('utf8_general_ci')->comment('imagen del usuario')->nullable();
            $table->string('password', 255)->collation('utf8_general_ci')->comment('token')->nullable();
            $table->string('key', 100)->collation('utf8_general_ci')->comment('key')->nullable();
            $table->char('status', 1)->collation('utf8_general_ci')->comment('status');
            $table->integer('id')->unsigned()->comment('rol del usuario');
            $table->foreign('id')->references('id')->on('role')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
