<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Gunakan DB::statement (Raw SQL) untuk menghindari bug Doctrine
        DB::statement('ALTER TABLE `pegawais` CHANGE `telepon` `posisi` VARCHAR(255) NULL;');
        // CATATAN: VARCHAR(255) adalah asumsi tipe data asli kolom telepon. 
        // Sesuaikan dengan tipe data yang benar jika aslinya TEXT/INT.
    }

    public function down(): void
    {
        // Membalikkan aksi
        DB::statement('ALTER TABLE `pegawais` CHANGE `posisi` `telepon` VARCHAR(255) NULL;');
    }
};
