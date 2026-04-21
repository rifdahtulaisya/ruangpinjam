<?php

namespace App\Imports;

use App\Models\Alat;
use App\Models\Kategori;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;

class AlatImport implements ToCollection, WithHeadingRow, SkipsOnError
{
    use Importable, SkipsErrors;
    
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            // Cari kategori berdasarkan nama
            $kategori = Kategori::where('nama_kategori', $row['kategori'])->first();
            
            if (!$kategori) {
                $this->onError(new \Exception("Baris " . ($index + 2) . ": Kategori '{$row['kategori']}' tidak ditemukan"));
                continue;
            }
            
            // Validasi kondisi
            $validKondisi = ['baik', 'rusak_ringan', 'rusak_berat', 'perlu_perbaikan'];
            $kondisi = strtolower($row['kondisi']);
            
            if (!in_array($kondisi, $validKondisi)) {
            $this->onError(new \Exception("Baris " . ($index + 2) . ": Kondisi tidak valid"));
            continue;
        }
            
            // Cek apakah alat sudah ada (berdasarkan nama dan lokasi)
            $existingAlat = Alat::where('nama_alat', $row['nama_alat'])
                ->where('lokasi', $row['lokasi'] ?? null)
                ->first();
            
            if ($existingAlat) {
                // Update stok jika alat sudah ada
                $existingAlat->update([
                    'stok' => $existingAlat->stok + ($row['stok'] ?? 0),
                    'kondisi' => $kondisi,
                ]);
            } else {
                // Buat data baru
                Alat::create([
                    'kategori_id' => $kategori->id,
                    'nama_alat' => $row['nama_alat'],
                    'kondisi' => $kondisi,
                    'stok' => $row['stok'] ?? 0,
                    'lokasi' => $row['lokasi'] ?? null,
                    'foto' => null,
                ]);
            }
        }
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
}