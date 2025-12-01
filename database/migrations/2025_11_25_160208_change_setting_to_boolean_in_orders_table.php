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
        Schema::table('orders', function (Blueprint $table) {
            // Mengubah tipe kolom 'setting' menjadi boolean. 
            // Default diset ke false (0).
            $table->boolean('setting')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     * Mengembalikan ke integer, yang merupakan tipe data asli untuk progress count.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Revert: Mengubah kembali ke integer (asumsi tipe data sebelumnya)
            $table->integer('setting')->default(0)->change();
        });
    }
};