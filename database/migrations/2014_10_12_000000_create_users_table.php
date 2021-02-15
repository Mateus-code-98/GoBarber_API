<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('path_avatar')->nullable();
            $table->string('type');
            $table->float('score')->nullable();
            $table->integer('stars_1')->nullable();
            $table->integer('stars_2')->nullable();
            $table->integer('stars_3')->nullable();
            $table->integer('stars_4')->nullable();
            $table->integer('stars_5')->nullable();
            $table->string('city')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('street')->nullable();
            $table->string('addressNumber')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
