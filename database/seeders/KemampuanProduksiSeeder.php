<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KemampuanProduksi;
use Illuminate\Support\Facades\DB;

class KemampuanProduksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel terlebih dahulu (optional)
        DB::table('tb_kemampuan_produksi')->truncate();
        
        // Data yang akan diinsert
        $data = [
            [
                'nama_kemampuan' => 'Print',
                'nilai_kemampuan' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kemampuan' => 'Packing & Finishing',
                'nilai_kemampuan' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert data
        foreach ($data as $item) {
            KemampuanProduksi::create($item);
        }

        // Atau gunakan query builder
        // DB::table('tb_kemampuan_produksi')->insert($data);

        $this->command->info('Seeder KemampuanProduksi berhasil dijalankan!');
        $this->command->info('Data yang ditambahkan:');
        
        foreach ($data as $item) {
            $this->command->line("  - {$item['nama_kemampuan']}: {$item['nilai_kemampuan']}");
        }
    }
}