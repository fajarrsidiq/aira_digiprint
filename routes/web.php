<?php

use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\SatuanController;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard petugas (semua petugas)
Route::middleware(['auth:petugas'])->get('/dashboard/petugas', [DashboardController::class, 'petugas'])->name('dashboard.petugas');

// Dashboard pelanggan
Route::middleware(['auth:pelanggan'])->get('/dashboard/pelanggan', function (){
    return view('pelanggan.dashboard', ['user' => auth()->guard('pelanggan')->user()]);
})->name('dashboard.pelanggan');

// Profile Pengguna
Route::middleware(['any.guard'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/password', [PasswordController::class, 'update'])->name('password.update');
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

// CRUD Bahan (Owner & Administrasi)
Route::middleware(['auth:petugas', 'level:Owner,Administrasi'])->group(function () {
    Route::get('/bahan', [BahanController::class, 'index'])->name('bahan.index');
    Route::get('/bahan/create', [BahanController::class, 'create'])->name('bahan.create');
    Route::post('/bahan', [BahanController::class, 'store'])->name('bahan.store');
    Route::get('/bahan/{bahan}/edit', [BahanController::class, 'edit'])->name('bahan.edit');
    Route::put('/bahan/{bahan}', [BahanController::class, 'update'])->name('bahan.update');
    Route::delete('/bahan/{bahan}', [BahanController::class, 'destroy'])->name('bahan.destroy');
});

// CRUD Bahan (Owner & Administrasi)
Route::middleware(['auth:petugas', 'level:Owner,Administrasi'])->group(function () {
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    Route::get('/produk/create', [ProdukController::class, 'create'])->name('produk.create');
    Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
    Route::get('/produk/{produk}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{produk}', [ProdukController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');
});


require __DIR__.'/auth.php';
