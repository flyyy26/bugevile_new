<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Hitung total dari orders
        $totals = DB::table('orders')
            ->selectRaw('SUM(hari) as total_hari, SUM(deadline) as total_deadline')
            ->first();

        // Update order_totals
        DB::table('order_totals')->update([
            'total_hari' => $totals->total_hari ?? 0,
            'total_deadline' => $totals->total_deadline ?? 0,
        ]);
    }

    public function down(): void
    {
        // Rollback → reset totals ke 0
        DB::table('order_totals')->update([
            'total_hari' => 0,
            'total_deadline' => 0,
        ]);
    }
};
