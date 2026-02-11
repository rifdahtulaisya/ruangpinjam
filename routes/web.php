<?php

use App\Http\Controllers\Admin\DataAlatController;
use App\Http\Controllers\Admin\DataPeminjamController;
use App\Http\Controllers\Admin\DataPerkelasController;
use App\Http\Controllers\Admin\DataPetugasController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\LogAktivitasController;
use App\Http\Controllers\Peminjam\DaftarAlatController;
use App\Http\Controllers\Peminjam\PeminjamanController;
use App\Http\Controllers\Petugas\KelolaPeminjamanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

 Route::get('dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('datapeminjam', DataPeminjamController::class);
    Route::resource('datapetugas', DataPetugasController::class);
    Route::get('dataperkelas', [DataPerkelasController::class, 'index'])->name('dataperkelas.index');
    Route::resource('datakategori', KategoriController::class);
    Route::resource('dataalat', DataAlatController::class);
    Route::get('logaktivitas', [LogAktivitasController::class, 'index'])
    ->name('logaktivitas.index');
});


Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    
    Route::get('dashboard', function () {
        return view('petugas.dashboard');
    })->name('dashboard');

    Route::get('kelolapeminjaman', [KelolaPeminjamanController::class, 'index'])
         ->name('kelolapeminjaman.index');
    
    // Pastikan semua route ada name-nya
    Route::get('kelolapeminjaman/{id}/detail', [KelolaPeminjamanController::class, 'detail'])
         ->name('kelolapeminjaman.detail');
    
    Route::post('kelolapeminjaman/{id}/konfirmasi', [KelolaPeminjamanController::class, 'konfirmasi'])
         ->name('kelolapeminjaman.konfirmasi');
    
    Route::post('kelolapeminjaman/{id}/verifikasi', [KelolaPeminjamanController::class, 'verifikasi'])
         ->name('kelolapeminjaman.verifikasi');
    
    Route::post('kelolapeminjaman/{id}/tegur', [KelolaPeminjamanController::class, 'tegur'])
         ->name('kelolapeminjaman.tegur');
    
    Route::post('kelolapeminjaman/{id}/langsung-kembali', [KelolaPeminjamanController::class, 'langsungKembali'])
         ->name('kelolapeminjaman.langsung-kembali');
    
    Route::post('kelolapeminjaman/{id}/upload-foto', [KelolaPeminjamanController::class, 'uploadFotoBukti'])
         ->name('kelolapeminjaman.upload-foto');
    
    Route::post('kelolapeminjaman/export', [KelolaPeminjamanController::class, 'export'])
         ->name('kelolapeminjaman.export');

Route::post('kelolapeminjaman/{id}/konfirmasi-pengembalian', [KelolaPeminjamanController::class, 'konfirmasiPengembalian'])
    ->name('kelolapeminjaman.konfirmasi-pengembalian');
    
         Route::post('kelolapeminjaman/{id}/setujui', [KelolaPeminjamanController::class, 'setujuiPeminjaman'])
     ->name('kelolapeminjaman.setujui');

Route::post('kelolapeminjaman/{id}/tolak', [KelolaPeminjamanController::class, 'tolakPeminjaman'])
     ->name('kelolapeminjaman.tolak');
     // Tambahkan route ini jika belum ada
Route::post('kelolapeminjaman/{id}/konfirmasi-pengembalian', [KelolaPeminjamanController::class, 'konfirmasiPengembalian'])
    ->name('kelolapeminjaman.konfirmasi-pengembalian');
    
    Route::post('/kelolapeminjaman/{id}/setujui', [KelolaPeminjamanController::class, 'setujuiPeminjaman'])
    ->name('kelolapeminjaman.setujui');

Route::post('/kelolapeminjaman/{id}/konfirmasi-pengembalian', [KelolaPeminjamanController::class, 'konfirmasiPengembalian'])
    ->name('kelolapeminjaman.konfirmasi-pengembalian');

Route::post('/kelolapeminjaman/{id}/tolak', [KelolaPeminjamanController::class, 'tolakPeminjaman'])
    ->name('kelolapeminjaman.tolak');

// Route untuk konfirmasi langsung (dari status dipinjam ke selesai)
Route::post('/kelolapeminjaman/{id}/langsung-selesai', [KelolaPeminjamanController::class, 'langsungSelesai'])
    ->name('kelolapeminjaman.langsung-selesai');
});

Route::middleware(['auth', 'role:peminjam'])->prefix('peminjam')->name('peminjam.')->group(function () {

    Route::get('dashboard', function () {
        return view('peminjam.dashboard');
    })->name('dashboard');

    // Daftar alat
    Route::get('daftaralat', [DaftarAlatController::class, 'index'])
        ->name('daftaralat.index'); 
    Route::post('daftaralat/pinjam', [DaftarAlatController::class, 'storePeminjaman'])
        ->name('daftaralat.pinjam'); 

        // Peminjaman / Riwayat
    Route::get('peminjaman', [PeminjamanController::class, 'index'])
        ->name('peminjaman.index');
         Route::post('peminjaman/{id}/kembalikan', [PeminjamanController::class, 'kembalikan'])
        ->name('peminjaman.kembalikan');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
