<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jenis_order', function (Blueprint $table) {
            $table->integer('harga_jual')
                  ->nullable()
                  ->after('harga_barang'); // opsional, biar rapi
        });
    }

    public function down(): void
    {
        Schema::table('jenis_order', function (Blueprint $table) {
            $table->dropColumn('harga_jual');
        });
    }
};