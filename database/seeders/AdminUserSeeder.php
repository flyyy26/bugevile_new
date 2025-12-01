<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['username' => 'bugevile'],
            [
                'name' => 'Administrator',
                'username' => 'bugevile',
                'email' => 'admin@local.test', // tambahkan
                'password' => Hash::make('admin2025'),
            ]
        );

    }
}
