<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_belanja', function (Blueprint $table) {
            // Menambahkan kolom foreign key ke jenis_orders
            $table->foreignId('jenis_order_id')
                  ->nullable() // bisa diisi null jika ada data lama
                  ->after('id')
                  ->constrained('jenis_orders')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('tb_belanja', function (Blueprint $table) {
            $table->dropForeign(['jenis_order_id']);
            $table->dropColumn('jenis_order_id');
        });
    }
};
