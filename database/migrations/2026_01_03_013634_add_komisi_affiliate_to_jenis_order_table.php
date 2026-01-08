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
            // Tambah kolom komisi_affiliate saja
            $table->decimal('komisi_affiliate', 15, 2)->default(0)->after('laba_bersih');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_order', function (Blueprint $table) {
            $table->dropColumn('komisi_affiliate');
        });
    }
};