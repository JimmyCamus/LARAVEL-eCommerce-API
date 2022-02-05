<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Products extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('name');
            $table->integer('price');
            $table->integer('stock');
            $table->tinyInteger('available');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
