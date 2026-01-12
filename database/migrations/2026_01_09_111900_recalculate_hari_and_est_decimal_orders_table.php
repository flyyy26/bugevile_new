<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            UPDATE orders
            SET 
                hari = ROUND(qty / 30.0, 2),
                est  = ROUND(qty / 30.0, 2)
        ");
    }

    public function down(): void
    {
        DB::statement("
            UPDATE orders
            SET 
                hari = 0.00,
                est  = 0.00
        ");
    }
};
