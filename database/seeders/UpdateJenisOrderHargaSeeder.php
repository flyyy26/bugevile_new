<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateJenisOrderHargaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jenis_order')
            ->whereNull('harga_jenis_pekerjaan_id')
            ->update([
                'harga_jenis_pekerjaan_id' => 1
            ]);
    }
}