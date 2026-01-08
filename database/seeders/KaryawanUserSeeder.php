<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KaryawanUserSeeder extends Seeder
{
    public function run()
    {
        // Cek kalau user dengan id 999 sudah ada
        $exists = DB::table('users')->where('id', 999)->exists();
        if (!$exists) {
            DB::table('users')->insert([
                'id' => 999,
                'name' => 'Karyawan Dummy',               // wajib
                'username' => 'karyawan',                 // sesuai kolommu
                'email' => 'karyawan@example.com',        // wajib karena NOT NULL
                'password' => Hash::make('karyawan123'), // bcrypt
                'role' => 'karyawan',                     // jika kolom role sudah ada
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
