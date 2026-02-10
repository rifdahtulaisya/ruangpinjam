<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Utama',
            'username' => 'admin',
            'email' => 'admin@ruangpinjam',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'is_blocked' => false,
        ]);

        User::create([
            'name' => 'Petugas Satu',
            'username' => 'petugas1',
            'email' => 'petugas@ruangpinjam',
            'password' => Hash::make('12345678'),
            'role' => 'petugas',
            'is_blocked' => false,
        ]);

        // 10 PEMINJAM
        for ($i = 1; $i <= 10; $i++) {

            User::create([
                'name' => 'Peminjam ' . $i,
                'username' => 'peminjam' . $i,
                'email' => 'peminjam' . $i . '@ruangpinjam.id',
                'password' => Hash::make('12345678'),
                'role' => 'peminjam',
                'is_blocked' => false,
            ]);

        }
    }
}
