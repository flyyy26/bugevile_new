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
        // Pastikan ada record di order_totals, jika tidak buat satu
        $orderTotalExists = DB::table('order_totals')->exists();
        
        if (!$orderTotalExists) {
            DB::table('order_totals')->insert(['id' => 1]);
        }
        
        // Hitung total qty dari tabel orders
        $totalQty = DB::table('orders')
            ->whereNotNull('qty')
            ->sum('qty');
        
        // Update total_qty di order_totals
        DB::table('order_totals')
            ->update(['total_qty' => $totalQty]);
        
        // Jika ingin per record (jika ada multiple records di order_totals)
        // $orderTotals = DB::table('order_totals')->get();
        // foreach ($orderTotals as $orderTotal) {
        //     DB::table('order_totals')
        //         ->where('id', $orderTotal->id)
        //         ->update(['total_qty' => $totalQty]);
        // }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset total_qty ke 0
        DB::table('order_totals')->update(['total_qty' => 0]);
    }
};