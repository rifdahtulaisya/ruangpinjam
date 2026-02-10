<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@pinjam.id'],
            [
                'name' => 'Admin Utama',
                'username' => 'admin',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'is_blocked' => false,
            ]
        );
    }
}
