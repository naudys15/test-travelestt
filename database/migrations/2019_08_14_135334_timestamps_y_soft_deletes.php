<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TimestampsYSoftDeletes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entity', function (Blueprint $table) {
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('characteristiccategory', function (Blueprint $table) {
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('characteristicentity', function (Blueprint $table) {
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('language', function (Blueprint $table) {
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('languagefield', function (Blueprint $table) {
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('unitduration', function (Blueprint $table) {
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('country', function (Blueprint $table) {
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('province', function (Blueprint $table) {
            $table->timestamps();
            $table->softDeletes();
        });
        // Schema::table('city', function (Blueprint $table) {
        //     $table->timestamps();
        //     $table->softDeletes();
        // });
        Schema::table('role', function (Blueprint $table) {
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('user', function (Blueprint $table) {
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('module', function (Blueprint $table) {
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('submodule', function (Blueprint $table) {
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('rangesubmodule', function (Blueprint $table) {
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
