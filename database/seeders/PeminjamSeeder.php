<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PeminjamSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {

            User::updateOrCreate(
                ['email' => 'peminjam'.$i.'@pinjam.id'],
                [
                    'name' => 'Peminjam '.$i,
                    'username' => 'peminjam'.$i,
                    'password' => Hash::make('12345678'),
                    'role' => 'peminjam',
                    'is_blocked' => false,
                ]
            );

        }
    }
}
