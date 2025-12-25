<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHargaJenisPekerjaanTable extends Migration
{
    public function up()
    {
        Schema::create('harga_jenis_pekerjaan', function (Blueprint $table) {
            $table->id();
            $table->integer('harga_setting');
            $table->integer('harga_print');
            $table->integer('harga_press');
            $table->integer('harga_cutting');
            $table->integer('harga_jahit');
            $table->integer('harga_finishing');
            $table->integer('harga_packing');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('harga_jenis_pekerjaan');
    }
}
