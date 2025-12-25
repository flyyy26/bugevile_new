<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggan_orders', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('pelanggan_id');
            $table->unsignedBigInteger('order_id');

            $table->timestamps();

            // Foreign key
            $table->foreign('pelanggan_id')
                  ->references('id')
                  ->on('pelanggan')
                  ->onDelete('cascade');

            $table->foreign('order_id')
                  ->references('id')
                  ->on('orders')
                  ->onDelete('cascade');

            // Supaya tidak bisa double data yg sama
            $table->unique(['pelanggan_id', 'order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggan_orders');
    }
};
