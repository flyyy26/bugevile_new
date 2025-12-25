<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('harga_affiliator', function (Blueprint $table) {
            $table->id();
            
            // Kolom Harga
            // Menggunakan decimal(15, 2) adalah standar terbaik untuk uang
            // Contoh: 15000.00
            $table->decimal('harga', 15, 2); 

            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('harga_affiliator');
    }
};