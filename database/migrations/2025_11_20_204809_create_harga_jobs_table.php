<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('harga_jobs', function (Blueprint $table) {
            $table->id();
            
            // Foreign Key ke tabel orders (Job utama)
            $table->foreignId('job_id')
                  ->constrained('orders')
                  ->onDelete('cascade');
            
            // Kolom Harga (DECIMAL: 10 total digit, 2 di belakang koma)
            $table->decimal('harga', 10, 2); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harga_jobs');
    }
};
