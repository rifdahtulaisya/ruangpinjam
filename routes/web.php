<?php

use App\Http\Controllers\Admin\DataAlatController;
use App\Http\Controllers\Admin\DataPeminjamController;
use App\Http\Controllers\Admin\DataPerkelasController;
use App\Http\Controllers\Admin\DataPetugasController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\LogAktivitasController;
use App\Http\Controllers\Admin\UserImportController;
use App\Http\Controllers\Peminjam\DaftarAlatController;
use App\Http\Controllers\Peminjam\PeminjamanController;
use App\Http\Controllers\Petugas\KelolaPeminjamanController;
use App\Http\Controllers\Peminjam\ProfilePController;
use App\Http\Controllers\Petugas\LaporanController;
use App\Http\Controllers\Petugas\ProfileController;
use Barryvdh\DomPDF\Facade\Pdf;
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

        // Import routes
Route::get('/admin/datapeminjam/import', [UserImportController::class, 'showImportForm'])
    ->name('admin.datapeminjam.import');
Route::post('/admin/datapeminjam/import', [UserImportController::class, 'import'])
    ->name('admin.datapeminjam.import.process');
Route::get('/admin/datapeminjam/import/template', [UserImportController::class, 'downloadTemplate'])
    ->name('admin.datapeminjam.import.template');
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
