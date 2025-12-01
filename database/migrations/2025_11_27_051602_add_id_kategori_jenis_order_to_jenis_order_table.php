<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('jenis_order', function (Blueprint $table) {
            // Tambah kolom setelah nama (opsional boleh dipindah)
            $table->unsignedBigInteger('id_kategori_jenis_order')->nullable()->after('nama_jenis');

            // Foreign key → kategori_jenis_order(id)
            $table->foreign('id_kategori_jenis_order')
                  ->references('id')
                  ->on('kategori_jenis_order')
                  ->onDelete('set null'); 
                  // bisa diganti → cascade, restrict, dll
        });
    }

    public function down()
    {
        Schema::table('jenis_order', function (Blueprint $table) {
            $table->dropForeign(['id_kategori_jenis_order']);
            $table->dropColumn('id_kategori_jenis_order');
        });
    }
};
