<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Alat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan; // <<< TAMBAHKAN INI

class PeminjamanTelatSeeder extends Seeder
{
    public function run(): void
    {
        
        // ==========================================
        // 1. CEK USER PEMINJAM1
        // ==========================================
        $peminjam1 = User::where('email', 'peminjam1@pinjam.id')->first();
        
        if (!$peminjam1) {
            $this->command->error('❌ User peminjam1@pinjam.id tidak ditemukan!');
            return;
        }
        
        // ==========================================
        // 2. CEK DATA ALAT
        // ==========================================
        $alatList = Alat::all();
        
        if ($alatList->isEmpty()) {
            $this->command->error('❌ Tabel alat kosong!');
            return;
        }
        
        $alat1 = $alatList->first();
        $alat2 = $alatList->count() > 1 ? $alatList->get(1) : $alatList->first();
        
        // ==========================================
        // 3. HAPUS DATA LAMA & RESET BLOKIR
        // ==========================================
        DB::table('peminjaman')->where('user_id', $peminjam1->id)->delete();
        
        DB::table('users')
            ->where('id', $peminjam1->id)
            ->update([
                'is_blocked' => false,
                'blocked_reason' => null,
                'blocked_at' => null,
                'unblocked_at' => null
            ]);
        
        // ==========================================
        // 4. BUAT PEMINJAMAN TELAT - STATUS DIPINJAM
        //    TANGGAL PENGEMBALIAN SUDAH LEWAT!
        // ==========================================
        
        $peminjaman = Peminjaman::create([
            'user_id' => $peminjam1->id,
            'alat_ids' => [$alat1->id, $alat2->id],
            'nama_alat' => ($alat1->nama_alat ?? 'Alat') . ', ' . ($alat2->nama_alat ?? 'Alat'),
            'tanggal_peminjaman' => Carbon::now()->subDays(7)->toDateString(),
            'tanggal_pengembalian' => Carbon::now()->subDays(3)->toDateString(),
            'tanggal_dikembalikan' => null,
            'status' => 'dipinjam',
            'keterangan' => 'PEMINJAMAN TELAT - Belum dikembalikan melebihi batas waktu',
            'created_at' => Carbon::now()->subDays(7),
            'updated_at' => Carbon::now()->subDays(7),
        ]);
        
        // ==========================================
        // 5. PANGGIL COMMAND BLOKIR - PERBAIKAN INI!
        // ==========================================
        $this->command->warn('⏳ Menjalankan blokir otomatis untuk user telat...');
        
        // ✅ CARA BENAR: Gunakan Artisan::call()
        Artisan::call('block:user-telat');
        
        // Tampilkan output dari command
        $output = Artisan::output();
        $this->command->info($output);
        
        // ==========================================
        // 6. TAMPILKAN HASIL
        // ==========================================
        $this->command->info('=========================================');
        $this->command->info('✅ SEEDER PEMINJAMAN TELAT BERHASIL!');
        $this->command->info('=========================================');
        $this->command->line('');
        
        // Refresh user data
        $peminjam1->refresh();
        
        $this->command->table(
            ['No', 'Peminjam', 'Alat', 'Tgl Kembali', 'Status', 'Telat', 'Diblokir?'],
            [
                [
                    '1',
                    $peminjam1->name,
                    substr($peminjaman->nama_alat, 0, 25) . '...',
                    Carbon::parse($peminjaman->tanggal_pengembalian)->format('d/m/Y'),
                    $peminjaman->status,
                    Carbon::parse($peminjaman->tanggal_pengembalian)->diffForHumans(['parts' => 1, 'syntax' => Carbon::DIFF_ABSOLUTE]) . ' yang lalu',
                    $peminjam1->is_blocked ? '✅ YA' : '❌ TIDAK',
                ],
            ]
        );
        
        $this->command->line('');
        
        if ($peminjam1->is_blocked) {
            $this->command->info('📋 DETAIL BLOKIR:');
            $this->command->line('   - Alasan  : ' . $peminjam1->blocked_reason);
            $this->command->line('   - Tanggal : ' . ($peminjam1->blocked_at ? $peminjam1->blocked_at->format('d/m/Y H:i:s') : '-'));
        } else {
            $this->command->error('❌ USER TIDAK DIBLOKIR! Jalankan manual: php artisan block:user-telat');
            
            // Jalankan ulang command untuk debug
            $this->command->warn('Mencoba menjalankan ulang command...');
            Artisan::call('block:user-telat', [], $this->command->getOutput());
        }
        
        $this->command->info('=========================================');
    }
}