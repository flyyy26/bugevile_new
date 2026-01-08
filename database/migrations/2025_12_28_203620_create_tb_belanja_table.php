<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_belanja', function (Blueprint $table) {
            $table->id();
            $table->decimal('bahan_harga', 15, 2)->default(0); // harga bahan
            $table->decimal('kertas_harga', 15, 2)->default(0); // harga kertas
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_belanja');
    }
};
