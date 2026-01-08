<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_asesoris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('belanja_id')->constrained('tb_belanja')->onDelete('cascade');
            $table->string('nama'); // nama asesoris
            $table->decimal('harga', 15, 2)->default(0); // harga asesoris
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_asesoris');
    }
};