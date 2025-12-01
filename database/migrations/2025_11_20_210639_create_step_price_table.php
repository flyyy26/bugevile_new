<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('step_price', function (Blueprint $table) {
            $table->id();
            $table->string('nama_step');   // nama step
            $table->integer('harga_step'); // harga step
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('step_price');
    }
};
