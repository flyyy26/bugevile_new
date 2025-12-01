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
        Schema::table('order_totals', function (Blueprint $table) {
            // Kolom Progress (Completed Units)
            $table->integer('total_finishing')->default(0)->after('total_jahit');
            $table->integer('total_packing')->default(0)->after('total_finishing');

            // Kolom Sisa (Remaining Units)
            $table->integer('total_sisa_finishing')->default(0)->after('total_sisa_jahit');
            $table->integer('total_sisa_packing')->default(0)->after('total_sisa_finishing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_totals', function (Blueprint $table) {
            $table->dropColumn([
                'total_finishing',
                'total_packing',
                'total_sisa_finishing',
                'total_sisa_packing'
            ]);
        });
    }
};
