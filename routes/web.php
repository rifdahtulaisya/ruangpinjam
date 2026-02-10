<?php

use App\Http\Controllers\Admin\DataAlatController;
use App\Http\Controllers\Admin\DataPeminjamController;
use App\Http\Controllers\Admin\DataPerkelasController;
use App\Http\Controllers\Admin\KategoriController;
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
    Route::get('dataperkelas', [DataPerkelasController::class, 'index'])->name('dataperkelas.index');
    Route::resource('datakategori', KategoriController::class);
    Route::resource('dataalat', DataAlatController::class);
});


Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {

    Route::get('dashboard', function () {
        return view('petugas.dashboard');
    })->name('dashboard');
});

Route::middleware(['auth', 'role:peminjam'])->prefix('peminjam')->name('peminjam.')->group(function () {

    Route::get('dashboard', function () {
        return view('peminjam.dashboard');
    })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
