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
            // Kolom Progress (Completed Units)
            $table->integer('finishing')->default(0)->after('jahit');
            $table->integer('packing')->default(0)->after('finishing');

            // Kolom Sisa (Remaining Units)
            $table->integer('sisa_finishing')->default(0)->after('sisa_jahit');
            $table->integer('sisa_packing')->default(0)->after('sisa_finishing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'finishing',
                'packing',
                'sisa_finishing',
                'sisa_packing'
            ]);
        });
    }
};