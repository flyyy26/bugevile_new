<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('jenis_order', function (Blueprint $table) {
            $table->foreignId('harga_jenis_pekerjaan_id')
                ->nullable()
                ->after('id_kategori_jenis_order')
                ->constrained('harga_jenis_pekerjaan')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('jenis_order', function (Blueprint $table) {
            $table->dropForeign(['harga_jenis_pekerjaan_id']);
            $table->dropColumn('harga_jenis_pekerjaan_id');
        });
    }
};
