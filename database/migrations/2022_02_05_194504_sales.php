<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Sales extends Migration
{
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->integer('product_id');
            $table->integer('user_id');
            $table->date('sale_date');
            $table->integer('price');
            $table->integer('amount');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
