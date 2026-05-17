<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SatuanController;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard petugas (semua petugas)
Route::middleware(['auth:petugas'])->get('/dashboard/petugas', [DashboardController::class, 'petugas'])->name('dashboard.petugas');

// Dashboard pelanggan
Route::middleware(['auth:pelanggan'])->get('/dashboard/pelanggan', function () {
    return view('pelanggan.dashboard', ['user' => auth()->guard('pelanggan')->user()]);
})->name('dashboard.pelanggan');

// Profile Pengguna
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// CRUD satuan (Owner & Administrasi)
Route::middleware(['auth:petugas', 'level:Owner,Administrasi'])->group(function () {
    Route::get('/satuan', [SatuanController::class, 'index'])->name('satuan.index');
    Route::get('/satuan/create', [SatuanController::class, 'create'])->name('satuan.create');
    Route::post('/satuan', [SatuanController::class, 'store'])->name('satuan.store');
    Route::get('/satuan/{satuan}/edit', [SatuanController::class, 'edit'])->name('satuan.edit');
    Route::put('/satuan/{satuan}', [SatuanController::class, 'update'])->name('satuan.update');
    Route::delete('/satuan/{satuan}', [SatuanController::class, 'destroy'])->name('satuan.destroy');
});

require __DIR__.'/auth.php';
