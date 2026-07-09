<?php

use App\Http\Controllers\AdminNotifikasiController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisPembayaranController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PelangganTransaksiController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\TransaksiController;

// Halaman Profile (index)
Route::get('/', [LandingController::class, 'index'])->name('landing.index');


// Dashboard petugas (semua petugas)
Route::middleware(['auth:petugas'])->get('/dashboard/petugas', [DashboardController::class, 'petugas'])->name('dashboard.petugas');

// Dashboard pelanggan
Route::middleware(['auth:pelanggan'])->get('/dashboard/pelanggan', function (){
    return view('pelanggan.dashboard', ['user' => auth()->guard('pelanggan')->user()]);
})->name('dashboard.pelanggan');

// Notifikasi
Route::middleware(['auth:petugas', 'level:Owner,Administrasi'])->group(function () {
    Route::get('/notifikasi', [AdminNotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/proses/{id}', [AdminNotifikasiController::class, 'proses'])->name('admin.notifikasi.proses');
});

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

// CRUD Pelanggan (Owner & Administrasi)
Route::middleware(['auth:petugas', 'level:Owner,Administrasi'])->group(function () {
    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
    Route::get('/pelanggan/create', [PelangganController::class, 'create'])->name('pelanggan.create');
    Route::post('/pelanggan', [PelangganController::class, 'store'])->name('pelanggan.store');
    Route::get('/pelanggan/{pelanggan}/edit', [PelangganController::class, 'edit'])->name('pelanggan.edit');
    Route::put('/pelanggan/{pelanggan}', [PelangganController::class, 'update'])->name('pelanggan.update');
    Route::delete('/pelanggan/{pelanggan}', [PelangganController::class, 'destroy'])->name('pelanggan.destroy');
});

// CRUD Petugas (Owner)
Route::middleware(['auth:petugas', 'level:Owner,Administrasi'])->group(function () {
    Route::get('/petugas', [PetugasController::class, 'index'])->name('petugas.index');
    Route::get('/petugas/create', [PetugasController::class, 'create'])->name('petugas.create');
    Route::post('/petugas', [PetugasController::class, 'store'])->name('petugas.store');
    Route::get('/petugas/{petugas}/edit', [PetugasController::class, 'edit'])->name('petugas.edit');
    Route::put('/petugas/{petugas}', [PetugasController::class, 'update'])->name('petugas.update');
    Route::delete('/petugas/{petugas}', [PetugasController::class, 'destroy'])->name('petugas.destroy');
});

// CRUD Jenis Pembayaran (Owner)
Route::middleware(['auth:petugas', 'level:Owner,Administrasi'])->group(function () {
    Route::get('/jenispembayaran', [JenisPembayaranController::class, 'index'])->name('jenispembayaran.index');
    Route::get('/jenispembayaran/create', [JenisPembayaranController::class, 'create'])->name('jenispembayaran.create');
    Route::post('/jenispembayaran', [JenisPembayaranController::class, 'store'])->name('jenispembayaran.store');
    Route::get('/jenispembayaran/{jenispembayaran}/edit', [JenisPembayaranController::class, 'edit'])->name('jenispembayaran.edit');
    Route::put('/jenispembayaran/{jenispembayaran}', [JenisPembayaranController::class, 'update'])->name('jenispembayaran.update');
    Route::delete('/jenispembayaran/{jenispembayaran}', [JenisPembayaranController::class, 'destroy'])->name('jenispembayaran.destroy');
});

// Transaksi (Owner & Administrasi)
Route::middleware(['auth:petugas', 'level:Owner,Administrasi'])->prefix('transaksi')->name('transaksi.')->group(function () {
    Route::get('/', [TransaksiController::class, 'index'])->name('index');
    Route::get('/create', [TransaksiController::class, 'create'])->name('create');
    Route::post('/', [TransaksiController::class, 'store'])->name('store');
    Route::get('/{id}', [TransaksiController::class, 'show'])->name('show');
    Route::delete('/{id}', [TransaksiController::class, 'destroy'])->name('destroy');
    // Alur penanganan berkas dan pencetakan invoice
    Route::get('/{id}/invoice', [TransaksiController::class, 'invoice'])->name('invoice');
    // Alur baru halaman pelunasan (Sudah otomatis bernama transaksi.pelunasan & transaksi.proses-pelunasan)
    Route::get('/{id}/pelunasan', [TransaksiController::class, 'halamanPelunasan'])->name('pelunasan');
    Route::put('/{id}/proses-pelunasan', [TransaksiController::class, 'prosesPelunasan'])->name('proses-pelunasan');
});

// Grup petugas untuk unduh desain
Route::middleware(['auth:petugas'])->group(function () {
    Route::get('/transaksi/download-desain/{id_detail}', [TransaksiController::class, 'downloadDesain'])->name('transaksi.download-desain');
});

// Khusus Petugas (Laporan)
Route::middleware(['auth:petugas', 'level:Owner'])->group(function () {
    Route::get('/laporan', [App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export', [App\Http\Controllers\LaporanController::class, 'export'])->name('laporan.export');
});

// Khusus Pelanggan
Route::middleware('auth:pelanggan')->group(function () {
    Route::get('/pesanan', [PelangganTransaksiController::class, 'index'])->name('pelanggan.pesanan');
    Route::post('/pesanan', [PelangganTransaksiController::class, 'store'])->name('pelanggan.pesanan.store');
    Route::get('/riwayat', [PelangganTransaksiController::class, 'riwayat'])->name('pelanggan.riwayat');
    Route::get('/invoice/{id}', [PelangganTransaksiController::class, 'invoice'])->name('pelanggan.invoice');
});

// Grup khusus untuk staf produksi & desain
Route::middleware(['auth:petugas', 'level:Desain,Produksi'])->prefix('staff')->group(function () {
    Route::get('/pesanan', [TransaksiController::class, 'indexProduksi'])->name('transaksi.produksi');
    // Alur upload desain final
    Route::post('/transaksi/upload-final/{id_detail}', [TransaksiController::class, 'uploadFinal'])->name('transaksi.upload-final');
    Route::put('/update-status/{id}', [TransaksiController::class, 'updateStatus'])->name('transaksi.update-status');
});



require __DIR__.'/auth.php';
