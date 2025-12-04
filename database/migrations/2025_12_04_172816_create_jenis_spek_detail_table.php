<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jenis_spek_detail', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenis_spek_detail');

            $table->string('gambar')->nullable();

            $table->unsignedBigInteger('id_jenis_spek');

            $table->foreign('id_jenis_spek')
                  ->references('id')
                  ->on('jenis_spek')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jenis_spek_detail');
    }
};
