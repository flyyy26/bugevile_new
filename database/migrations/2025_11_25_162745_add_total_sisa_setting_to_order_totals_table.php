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
        Schema::table('order_totals', function (Blueprint $table) {
            // Tambahkan kolom baru
            // unsignedBigInteger digunakan untuk menyimpan jumlah (count) yang nilainya tidak akan negatif
            $table->unsignedBigInteger('total_sisa_setting')->default(0)->after('total_setting');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_totals', function (Blueprint $table) {
            // Hapus kolom jika rollback
            $table->dropColumn('total_sisa_setting');
        });
    }
};