@extends('layouts.app')

@section('title', 'Tambah Petugas')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-6">Tambah Petugas Baru</h2>
    
    <form action="{{ route('petugas.store') }}" method="POST" novalidate>
        @csrf

        <div class="mb-4">
            <label class="block font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-600">*</span></label>
            <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" class="w-full border p-2 rounded @error('nama_lengkap') border-red-500 bg-red-50 @enderror" autofocus oninput="generateUsername(this.value)">
            @error('nama_lengkap')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block font-medium text-gray-700 mb-1">Username <span class="text-red-600">*</span></label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" class="w-full border p-2 rounded @error('username') border-red-500 bg-red-50 @enderror">
                @error('username')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>
            
            <div>
                <label class="block font-medium text-gray-700 mb-1">Email (opsional)</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full border p-2 rounded @error('email') border-red-500 bg-red-50 @enderror">
                @error('email')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="block font-medium text-gray-700 mb-1">Level <span class="text-red-600">*</span></label>
            <select name="level" class="w-full border p-2 rounded @error('level') border-red-500 bg-red-50 @enderror">
                <option value="">Pilih Level Tugas</option>
                <option value="Administrasi" {{ old('level') == 'Administrasi' ? 'selected' : '' }}>Administrasi</option>
                <option value="Desain" {{ old('level') == 'Desain' ? 'selected' : '' }}>Desain</option>
                <option value="Produksi" {{ old('level') == 'Produksi' ? 'selected' : '' }}>Produksi</option>
            </select>
            @error('level')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block font-medium text-gray-700 mb-1">Password <span class="text-red-600">*</span></label>
                <input type="password" name="password" class="w-full border p-2 rounded @error('password') border-red-500 bg-red-50 @enderror">
                @error('password')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>
            
            <div>
                <label class="block font-medium text-gray-700 mb-1">Konfirmasi Password <span class="text-red-600">*</span></label>
                <input type="password" name="password_confirmation" class="w-full border p-2 rounded @error('password') border-red-500 bg-red-50 @enderror">
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
            <a href="{{ route('petugas.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">Batal</a>
        </div>
    </form>
</div>

<script>
    function generateUsername(nama) {
        if (!nama) {
            document.getElementById('username').value = '';
            return;
        }

        // 1. Ubah ke huruf kecil semua
        // 2. Hapus simbol, sisakan huruf, angka, dan spasi
        // 3. Gabungkan semua kata (hilangkan spasi)
        let usernameSaran = nama.toLowerCase()
                                .replace(/[^a-z0-9 ]/g, '')
                                .replace(/\s+/g, '');

        // 4. Generate angka acak 2 digit (10-99)
        let angkaAcak = Math.floor(10 + Math.random() * 90); 

        // 5. Set hasil ke input username
        document.getElementById('username').value = usernameSaran + angkaAcak;
    }
</script>
@endsection