<?php

use App\Http\Controllers\Admin\DataPeminjamController;
use App\Http\Controllers\Admin\DataPerkelasController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

 Route::get('dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('datapeminjam', DataPeminjamController::class);
    Route::get('dataperkelas', [DataPerkelasController::class, 'index'])->name('dataperkelas.index');
    Route::resource('datakategori', KategoriController::class);
});

Route::middleware(['auth', 'role:petugas'])->group(function () {

    Route::get('/petugas/dashboard', function () {
        return view('petugas.dashboard');
    })->name('petugas.dashboard');
});

Route::middleware(['auth', 'role:peminjam'])->group(function () {

    Route::get('/peminjam/dashboard', function () {
        return view('peminjam.dashboard');
    })->name('peminjam.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
