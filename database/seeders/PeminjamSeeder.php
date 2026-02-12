<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PeminjamSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'peminjam@pinjam.id'],
            [
                'name' => 'Peminjam',
                'username' => 'peminjam',
                'password' => Hash::make('12345678'),
                'role' => 'peminjam',
            ]
        );
    }
}
