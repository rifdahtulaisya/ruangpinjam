<?php

use App\Http\Controllers\Admin\DataAlatController;
use App\Http\Controllers\Admin\DataPeminjamController;
use App\Http\Controllers\Admin\DataPerkelasController;
use App\Http\Controllers\Admin\DataPetugasController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\LogAktivitasController;
use App\Http\Controllers\Admin\ImportAlatController;
use App\Http\Controllers\Admin\UserImportController;
use App\Http\Controllers\Peminjam\DaftarAlatController;
use App\Http\Controllers\Peminjam\PeminjamanController;
use App\Http\Controllers\Petugas\KelolaPeminjamanController;
use App\Http\Controllers\Peminjam\ProfilePController;
use App\Http\Controllers\Petugas\LaporanController;
use App\Http\Controllers\Petugas\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Import routes untuk data alat
    Route::get('dataalat/import', [ImportAlatController::class, 'showImportForm'])
        ->name('dataalat.import');
    Route::post('dataalat/import', [ImportAlatController::class, 'import'])
        ->name('dataalat.import.process');
    Route::get('dataalat/import/template', [ImportAlatController::class, 'downloadTemplate'])
        ->name('dataalat.import.template');

        // Import & Export untuk data petugas
    Route::get('datapetugas/import', [DataPetugasController::class, 'showImportForm'])
        ->name('datapetugas.import');
    Route::post('datapetugas/import', [DataPetugasController::class, 'import'])
        ->name('datapetugas.import.process');
    Route::get('datapetugas/import/template', [DataPetugasController::class, 'downloadTemplate'])
        ->name('datapetugas.import.template');
    Route::get('datapetugas/export', [DataPetugasController::class, 'export'])
        ->name('datapetugas.export');
    
            // Import & Export untuk data peminjam
    Route::get('datapeminjam/import', [DataPeminjamController::class, 'showImportForm'])
        ->name('datapeminjam.import');
    Route::post('datapeminjam/import', [DataPeminjamController::class, 'import'])
        ->name('datapeminjam.import.process');
    Route::get('datapeminjam/import/template', [DataPeminjamController::class, 'downloadTemplate'])
        ->name('datapeminjam.import.template');
    Route::get('datapeminjam/export', [DataPeminjamController::class, 'export'])
        ->name('datapeminjam.export');


    Route::resource('datapeminjam', DataPeminjamController::class);
    Route::resource('datapetugas', DataPetugasController::class);
    Route::get('dataperkelas', [DataPerkelasController::class, 'index'])->name('dataperkelas.index');
    Route::post('datakategori/store-many', [KategoriController::class, 'storeMany'])
         ->name('datakategori.storeMany');
    Route::resource('datakategori', KategoriController::class);
    Route::resource('dataalat', DataAlatController::class);
    Route::get('logaktivitas', [LogAktivitasController::class, 'index'])
        ->name('logaktivitas.index');

});


Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {

    Route::get('dashboard', function () {
        return view('petugas.dashboard');
    })->name('dashboard');

    // Route untuk Laporan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::post('/export', [LaporanController::class, 'export'])->name('export');
    });


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

    // Route untuk verifikasi mandiri (reset ke dipinjam)
    Route::post('kelolapeminjaman/{id}/verifikasi-mandiri', [KelolaPeminjamanController::class, 'verifikasiMandiri'])
        ->name('kelolapeminjaman.verifikasi-mandiri');
    // ============ ROUTE PROFIL ============
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::post('profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');

    
});

Route::middleware(['auth', 'role:peminjam'])->prefix('peminjam')->name('peminjam.')->group(function () {

    Route::get('dashboard', function () {
        return view('peminjam.dashboard');
    })->name('dashboard');

    // Route untuk cek barang dipinjam
    Route::get('peminjaman/cek-barang-dipinjam', [PeminjamanController::class, 'cekBarangDipinjam'])
        ->name('peminjaman.cek-barang-dipinjam');

    // Route untuk get barang dipinjam dengan filter
    Route::get('peminjaman/barang-dipinjam', [PeminjamanController::class, 'getBarangDipinjam'])
        ->name('peminjaman.barang-dipinjam');

    // Route untuk pengembalian mandiri
    Route::post('pengembalian-mandiri', [PeminjamanController::class, 'pengembalianMandiri'])
        ->name('pengembalian.mandiri');

    // Route untuk get detail teguran
    Route::get('peminjaman/{id}/teguran', [PeminjamanController::class, 'getDetailTeguran'])
        ->name('peminjaman.teguran');

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
        // ============ ROUTE PROFIL ============
    Route::get('profile', [ProfilePController::class, 'index'])->name('profile.index');
    Route::put('profile', [ProfilePController::class, 'update'])->name('profile.update');
    Route::post('profile/photo', [ProfilePController::class, 'updatePhoto'])->name('profile.photo');
    Route::post('profile/change-password', [ProfilePController::class, 'changePassword'])->name('profile.change-password');
});

require __DIR__ . '/auth.php';
