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

            // jika kolom punya foreign key — hapus dulu FK nya
            // (kalau tidak yakin, aman untuk tetap ditulis)
            try {
                $table->dropForeign(['id_jenis_bahan']);
                $table->dropForeign(['id_jenis_kerah']);
                $table->dropForeign(['id_jenis_pola']);
                $table->dropForeign(['id_jenis_jahitan']);
            } catch (\Exception $e) {
                // abaikan jika tidak ada foreign key
            }

            // hapus kolomnya
            $table->dropColumn([
                'id_jenis_bahan',
                'id_jenis_kerah',
                'id_jenis_pola',
                'id_jenis_jahitan'
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            // kembalikan kalau di-rollback
            $table->unsignedBigInteger('id_jenis_bahan')->nullable();
            $table->unsignedBigInteger('id_jenis_kerah')->nullable();
            $table->unsignedBigInteger('id_jenis_pola')->nullable();
            $table->unsignedBigInteger('id_jenis_jahitan')->nullable();

            // foreign key optional, kalau ingin dikembalikan juga
            $table->foreign('id_jenis_bahan')->references('id')->on('jenis_bahan')->nullOnDelete();
            $table->foreign('id_jenis_kerah')->references('id')->on('jenis_kerah')->nullOnDelete();
            $table->foreign('id_jenis_pola')->references('id')->on('jenis_pola')->nullOnDelete();
            $table->foreign('id_jenis_jahitan')->references('id')->on('jenis_jahitan')->nullOnDelete();
        });
    }
};
