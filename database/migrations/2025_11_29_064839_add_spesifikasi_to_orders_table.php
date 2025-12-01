<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->unsignedBigInteger('id_jenis_bahan')->nullable()->after('keterangan');
            $table->unsignedBigInteger('id_jenis_pola')->nullable()->after('id_jenis_bahan');
            $table->unsignedBigInteger('id_jenis_kerah')->nullable()->after('id_jenis_pola');
            $table->unsignedBigInteger('id_jenis_jahitan')->nullable()->after('id_jenis_kerah');

            // Foreign Keys
            $table->foreign('id_jenis_bahan')->references('id')->on('jenis_bahan')->nullOnDelete();
            $table->foreign('id_jenis_pola')->references('id')->on('jenis_pola')->nullOnDelete();
            $table->foreign('id_jenis_kerah')->references('id')->on('jenis_kerah')->nullOnDelete();
            $table->foreign('id_jenis_jahitan')->references('id')->on('jenis_jahitan')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['id_jenis_bahan']);
            $table->dropForeign(['id_jenis_pola']);
            $table->dropForeign(['id_jenis_kerah']);
            $table->dropForeign(['id_jenis_jahitan']);

            $table->dropColumn([
                'id_jenis_bahan',
                'id_jenis_pola',
                'id_jenis_kerah',
                'id_jenis_jahitan'
            ]);
        });
    }
};
