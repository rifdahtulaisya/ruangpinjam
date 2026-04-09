<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PetugasSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'petugas@pinjam.id'],
            [
                'name' => 'Petugas',
                'username' => 'petugas',
                'password' => Hash::make('12345678'),
                'role' => 'petugas',
            ]
        );
    }
}
