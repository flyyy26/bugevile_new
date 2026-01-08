<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jenis_order', function (Blueprint $table) {
            $table->decimal('harga_barang', 15, 2)->default(0)->after('nama_jenis'); 
        });
    }

    public function down(): void
    {
        Schema::table('jenis_order', function (Blueprint $table) {
            $table->dropColumn('harga_barang');
        });
    }
};