@extends('layouts.app')

@section('title', 'Tambah Pelanggan')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-4">Tambah Pelanggan Baru</h2>
    <form action="{{ route('pelanggan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="block font-medium mb-1">Nama Lengkap <span class="text-red-600">*</span></label>
            <input type="text" id="nama_pelanggan" name="nama_pelanggan" class="w-full border rounded p-2" required autofocus oninput="generateUsername(this.value)">
        </div>

        <div class="mb-3">
            <label class="block font-medium mb-1">Username <span class="text-red-600">*</span></label>
            <input type="text" id="username" name="username" class="w-full border rounded p-2" required>
            <p class="text-xs text-gray-500">Username akan digunakan pelanggan untuk login.</p>
        </div>

        <div class="mb-3">
            <label class="block font-medium mb-1">Alamat</label>
            <textarea name="alamat" rows="2" class="w-full border rounded p-2"></textarea>
        </div>

        <div class="mb-3">
            <label class="block font-medium mb-1">No. Telepon <span class="text-red-600">*</span></label>
            <input type="text" name="no_telpon" class="w-full border rounded p-2">
        </div>

        <div class="bg-blue-50 p-3 rounded mb-3 text-sm text-blue-700">
            <i class="fas fa-info-circle mr-1"></i>Password default untuk pelanggan ini adalah<strong>pelanggan123</strong>. Pelanggan dapat mengubah password setelah login.
        </div>

        <div class="flex justify-end gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
            <a href="{{ route('pelanggan.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded">Batal</a>
        </div>
    </form>
</div>

<script>
    function generateUsername(nama) {
        // Jika input nama kosong, kosongkan juga input username
        if (!nama) {
            document.getElementById('username').value = '';
            return;
        }

        // 1. Ubah tulisan menjadi huruf kecil semua
        // 2. Bersihkan karakter simbol/tanda baca, sisakan hanya huruf, angka, dan spasi
        // 3. Hapus semua spasi agar menyatu menjadi satu kata tunggal
        let usernameSaran = nama.toLowerCase()
                                .replace(/[^a-z0-9 ]/g, '')
                                .replace(/\s+/g, '');

        // 4. Buat 2 digit angka acak (dari angka 10 sampai 99) agar username lebih unik di database
        let angkaAcak = Math.floor(10 + Math.random() * 90); 

        // 5. Masukkan gabungan nama & angka acak tersebut langsung ke input username
        document.getElementById('username').value = usernameSaran + angkaAcak;
    }
</script>
@endsection