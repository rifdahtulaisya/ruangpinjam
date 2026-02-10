<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PetugasSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 3; $i++) {

            User::updateOrCreate(
                ['email' => 'petugas'.$i.'@pinjam.id'],
                [
                    'name' => 'Petugas '.$i,
                    'username' => 'petugas'.$i,
                    'password' => Hash::make('12345678'),
                    'role' => 'petugas',
                    'is_blocked' => false,
                ]
            );

        }
    }
}
