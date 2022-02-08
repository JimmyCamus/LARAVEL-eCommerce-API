<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('name');
            $table->string('country');
            $table->string('state')->nullable();
            $table->string('city');
            $table->string('address');
            $table->string('email')->unique();
            $table->string('password');
            $table->tinyInteger('rol');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
