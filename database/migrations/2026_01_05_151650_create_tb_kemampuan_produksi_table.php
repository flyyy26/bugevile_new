<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_kemampuan_produksi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kemampuan', 100);
            $table->integer('nilai_kemampuan');
            $table->timestamps();
            
            // Optional: Tambahkan indeks untuk performa query
            $table->index('nama_kemampuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_kemampuan_produksi');
    }
};