<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jenis_spek', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenis_spek');

            $table->unsignedBigInteger('id_kategori_jenis_order');

            $table->foreign('id_kategori_jenis_order')
                  ->references('id')
                  ->on('kategori_jenis_order')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jenis_spek');
    }
};