<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            UPDATE orders
            SET deadline = ROUND(qty / 30.0, 2)
            WHERE id IN (197, 198, 200, 203, 204, 205, 229, 230)
        ");
    }

    public function down(): void
    {
        // Rollback aman → set ke 0.00 (atau NULL kalau kolom nullable)
        DB::statement("
            UPDATE orders
            SET deadline = 0.00
            WHERE id IN (197, 198, 200, 203, 204, 205, 229, 230)
        ");
    }
};
