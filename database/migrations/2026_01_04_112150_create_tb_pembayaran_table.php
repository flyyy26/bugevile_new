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
        Schema::create('tb_pembayaran', function (Blueprint $table) {
            $table->id();
            
            // Foreign key ke pelanggan
            $table->foreignId('pelanggan_id')
                  ->constrained('pelanggan')
                  ->onDelete('cascade');
            
            // Foreign key ke order
            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->onDelete('cascade');
            
            // Data pembayaran
            $table->decimal('dp', 15, 2)->default(0);
            $table->decimal('sisa_bayar', 15, 2)->default(0);
            $table->decimal('harus_dibayar', 15, 2)->default(0);
            
            // Status pembayaran: true = lunas, false = belum lunas
            $table->boolean('status')->default(false);
            
            // Timestamps
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['pelanggan_id', 'order_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_pembayaran');
    }
};