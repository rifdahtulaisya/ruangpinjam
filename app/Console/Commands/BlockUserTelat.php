<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;

class BlockUserTelat extends Command
{
    protected $signature = 'block:user-telat';
    protected $description = 'Blokir user yang memiliki peminjaman telat (status dipinjam dan melewati tanggal pengembalian)';

    public function handle()
    {
        $this->info('🔍 Mencari user dengan peminjaman telat...');
        
        // CARI SEMUA USER YANG PUNYA PEMINJAMAN TELAT
        $usersTelat = User::whereHas('peminjaman', function($query) {
            $query->where('status', 'dipinjam')
                  ->whereDate('tanggal_pengembalian', '<', Carbon::today());
        })->get();
        
        $this->info('📊 Ditemukan ' . $usersTelat->count() . ' user dengan peminjaman telat');
        
        $blockedUsers = [];
        
        foreach ($usersTelat as $user) {
            if (!$user->is_blocked) {
                $peminjamanTelat = $user->peminjaman()
                    ->where('status', 'dipinjam')
                    ->whereDate('tanggal_pengembalian', '<', Carbon::today())
                    ->first();
                
                $hariTelat = Carbon::parse($peminjamanTelat->tanggal_pengembalian)->diffInDays(Carbon::today());
                
                $user->blockBecauseTelat(
                    "Akun diblokir otomatis karena memiliki peminjaman telat {$hariTelat} hari"
                );
                
                $blockedUsers[$user->id] = $user->name;
                $this->warn("  ⛔ User {$user->name} diblokir! (Telat {$hariTelat} hari)");
            }
        }
        
        $this->info('✅ Selesai! ' . count($blockedUsers) . ' user diblokir.');
        
        // CEK USER YANG SUDAH TIDAK TELAT TAPI MASIH BLOKIR
        $this->info("\n🔍 Mencari user yang bisa di-unblock...");
        
        $usersBlokir = User::where('is_blocked', true)->get();
        $unblockedUsers = [];
        
        foreach ($usersBlokir as $user) {
            if (!$user->masihMemilikiPeminjamanTelat()) {
                $user->unblock("Akun dipulihkan otomatis - Tidak ada peminjaman telat aktif");
                $unblockedUsers[$user->id] = $user->name;
                $this->info(" User {$user->name} dipulihkan!");
            }
        }
        
        $this->info('Selesai! ' . count($unblockedUsers) . ' user dipulihkan.');
        
        // TAMPILKAN TABEL
        if (count($blockedUsers) > 0 || count($unblockedUsers) > 0) {
            $this->table(
                ['ID', 'Nama', 'Status', 'Keterangan'],
                array_merge(
                    collect($blockedUsers)->map(function($name, $id) {
                        return [$id, $name, 'DIBLOKIR', 'Peminjaman telat'];
                    })->values()->toArray(),
                    collect($unblockedUsers)->map(function($name, $id) {
                        return [$id, $name, 'DIPULIHKAN', 'Tidak ada peminjaman telat'];
                    })->values()->toArray()
                )
            );
        }
    }
}