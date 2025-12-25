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
        Schema::table('pelanggan', function (Blueprint $table) {
            // Tambahkan kolom id_affiliates dengan default null
            $table->unsignedBigInteger('id_affiliates')
                  ->nullable()
                  ->default(null)
                  ->after('id');
            
            // Tambahkan foreign key constraint
            $table->foreign('id_affiliates')
                  ->references('id')
                  ->on('affiliates')
                  ->onDelete('SET NULL') // atau 'CASCADE' atau 'RESTRICT'
                  ->onUpdate('CASCADE');
            
            // Tambahkan index untuk performa
            $table->index('id_affiliates');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['id_affiliates']);
            
            // Hapus index
            $table->dropIndex(['id_affiliates']);
            
            // Hapus kolom
            $table->dropColumn('id_affiliates');
        });
    }
};
