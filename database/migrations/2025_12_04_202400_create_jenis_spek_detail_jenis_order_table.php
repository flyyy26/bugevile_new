<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jenis_spek_detail_jenis_order', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('jenis_spek_detail_id');
            $table->unsignedBigInteger('jenis_order_id');
            
            $table->foreign('jenis_spek_detail_id')
                  ->references('id')
                  ->on('jenis_spek_detail')
                  ->onDelete('cascade');
            
            $table->foreign('jenis_order_id')
                  ->references('id')
                  ->on('jenis_order')
                  ->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jenis_spek_detail_jenis_order');
    }
};
