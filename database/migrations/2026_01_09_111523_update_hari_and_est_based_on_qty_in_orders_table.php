<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update hari dan est = qty / 30
        DB::statement("
            UPDATE orders
            SET 
                hari = qty / 30,
                est  = qty / 30
        ");
    }

    public function down(): void
    {
        // Rollback tidak bisa mengembalikan nilai lama
        // Jadi kita set ke 0 (atau NULL sesuai kebutuhan)
        DB::statement("
            UPDATE orders
            SET 
                hari = 0,
                est  = 0
        ");
    }
};
