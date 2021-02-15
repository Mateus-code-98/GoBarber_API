<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarbersTable extends Migration
{

    public function up()
    {
        Schema::create('barbers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('phone_number')->nullable();
            $table->float('score')->nullable();
            $table->string('path_avatar')->nullable();
            $table->uuid('barbershop_id');
            $table->foreign('barbershop_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('barbers');
    }
}
