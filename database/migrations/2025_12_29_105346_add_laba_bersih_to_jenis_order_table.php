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
        Schema::table('jenis_order', function (Blueprint $table) {
            $table->integer('laba_bersih')
                  ->nullable()
                  ->after('harga_jual'); // opsional, biar rapi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_order', function (Blueprint $table) {
            $table->dropColumn('laba_bersih');
        });
    }
};
