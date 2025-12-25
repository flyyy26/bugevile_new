<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Mengubah tipe kolom 'hari' menjadi double (untuk menyimpan desimal)
            $table->decimal('hari')->change(); 
            
            // Mengubah tipe kolom 'deadline' menjadi decimal
            $table->decimal('deadline')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Mengubah kembali ke tipe float/decimal jika di-rollback (asumsi)
            $table->decimal('hari')->change();
            $table->decimal('deadline')->change();
        });
    }
};
