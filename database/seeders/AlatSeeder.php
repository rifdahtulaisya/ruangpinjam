<?php

namespace Database\Seeders;

use App\Models\Alat;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class AlatSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada data kategori
        if (Kategori::count() == 0) {
            $kategori = [
                ['nama_kategori' => 'Perkakas'],
                ['nama_kategori' => 'Elektronik'],
                ['nama_kategori' => 'Alat Ukur'],
                ['nama_kategori' => 'Peralatan Listrik'],
                ['nama_kategori' => 'Peralatan Mekanik'],
            ];

            foreach ($kategori as $data) {
                Kategori::create($data);
            }
        }

        $kategoriList = Kategori::all();
        
        $alat = [
            [
                'nama_alat' => 'Bor Listrik',
                'kondisi' => 'baik',
                'stok' => 5,
                'lokasi' => 'Gudang Utama'
            ],
            [
                'nama_alat' => 'Multimeter Digital',
                'kondisi' => 'baik',
                'stok' => 8,
                'lokasi' => 'Rak Elektronik'
            ],
            [
                'nama_alat' => 'Tang Ampere',
                'kondisi' => 'rusak_ringan',
                'stok' => 3,
                'lokasi' => 'Rak Alat Ukur'
            ],
            [
                'nama_alat' => 'Gergaji Mesin',
                'kondisi' => 'baik',
                'stok' => 2,
                'lokasi' => 'Gudang Kayu'
            ],
            [
                'nama_alat' => 'Kompresor Angin',
                'kondisi' => 'perlu_perbaikan',
                'stok' => 1,
                'lokasi' => 'Gudang Besar'
            ],
        ];

        foreach ($alat as $data) {
            // HANYA data yang sesuai dengan kolom yang ada
            Alat::create(array_merge($data, [
                'kategori_id' => $kategoriList->random()->id
            ]));
        }

        $this->command->info('Seeder alat berhasil ditambahkan!');
    }
}