<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Reset semua nilai total_nilai_setting menjadi 0
        DB::table('order_totals')->update(['total_sisa_setting' => 0]);
        
        // Atau jika ingin menghapus record yang total_nilai_setting = 1:
        // DB::table('order_totals')->where('total_nilai_setting', 1)->delete();
    }

    public function down(): void
    {
        // Tidak bisa mengembalikan data yang sudah dihapus
        // Tapi bisa set default value
        DB::table('order_totals')->update(['total_sisa_setting' => 0]);
    }
};