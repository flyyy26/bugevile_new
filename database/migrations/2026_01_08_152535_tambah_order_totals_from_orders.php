<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pastikan record dengan id 1 ada
        $exists = DB::table('order_totals')->where('id', 1)->exists();
        
        if (!$exists) {
            DB::table('order_totals')->insert(['id' => 1]);
        }
        
        // Hitung semua total dari tabel orders
        $totals = DB::table('orders')
            ->selectRaw('
                COALESCE(SUM(qty), 0) as total_qty,
                COALESCE(SUM(hari), 0) as total_hari,
                COALESCE(SUM(deadline), 0) as total_deadline,
                COALESCE(SUM(print), 0) as total_print,
                COALESCE(SUM(press), 0) as total_press,
                COALESCE(SUM(cutting), 0) as total_cutting,
                COALESCE(SUM(jahit), 0) as total_jahit,
                COALESCE(SUM(finishing), 0) as total_finishing,
                COALESCE(SUM(packing), 0) as total_packing,
                COALESCE(SUM(sisa_print), 0) as total_sisa_print,
                COALESCE(SUM(sisa_press), 0) as total_sisa_press,
                COALESCE(SUM(sisa_cutting), 0) as total_sisa_cutting,
                COALESCE(SUM(sisa_jahit), 0) as total_sisa_jahit,
                COALESCE(SUM(sisa_finishing), 0) as total_sisa_finishing,
                COALESCE(SUM(sisa_packing), 0) as total_sisa_packing
            ')
            ->first();
        
        // Update record dengan id 1
        DB::table('order_totals')
            ->where('id', 1)
            ->update([
                'total_qty' => $totals->total_qty ?? 0,
                'total_hari' => $totals->total_hari ?? 0,
                'total_deadline' => $totals->total_deadline ?? 0,
                'total_print' => $totals->total_print ?? 0,
                'total_press' => $totals->total_press ?? 0,
                'total_cutting' => $totals->total_cutting ?? 0,
                'total_jahit' => $totals->total_jahit ?? 0,
                'total_finishing' => $totals->total_finishing ?? 0,
                'total_packing' => $totals->total_packing ?? 0,
                'total_setting' => 13, // Diatur tetap 13
                'total_sisa_setting' => 0, // Diatur tetap 0
                'total_sisa_print' => $totals->total_sisa_print ?? 0,
                'total_sisa_press' => $totals->total_sisa_press ?? 0,
                'total_sisa_cutting' => $totals->total_sisa_cutting ?? 0,
                'total_sisa_jahit' => $totals->total_sisa_jahit ?? 0,
                'total_sisa_finishing' => $totals->total_sisa_finishing ?? 0,
                'total_sisa_packing' => $totals->total_sisa_packing ?? 0,
                'updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset semua nilai ke 0 (kecuali total_setting dan total_sisa_setting)
        DB::table('order_totals')
            ->where('id', 1)
            ->update([
                'total_qty' => 0,
                'total_hari' => 0,
                'total_deadline' => 0,
                'total_print' => 0,
                'total_press' => 0,
                'total_cutting' => 0,
                'total_jahit' => 0,
                'total_finishing' => 0,
                'total_packing' => 0,
                'total_setting' => 13, // Tetap 13
                'total_sisa_setting' => 0, // Tetap 0
                'total_sisa_print' => 0,
                'total_sisa_press' => 0,
                'total_sisa_cutting' => 0,
                'total_sisa_jahit' => 0,
                'total_sisa_finishing' => 0,
                'total_sisa_packing' => 0,
            ]);
    }
};