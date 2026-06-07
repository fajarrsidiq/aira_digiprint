@extends('layouts.app')

@section('title', 'Edit Petugas')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-6">Edit Petugas</h2>
    
    <form action="{{ route('petugas.update', $petugas->id_petugas) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-600">*</span></label>
            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $petugas->nama_lengkap) }}" class="w-full border p-2 rounded @error('nama_lengkap') border-red-500 bg-red-50 @enderror" autofocus>
            @error('nama_lengkap')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block font-medium text-gray-700 mb-1">Username <span class="text-red-600">*</span></label>
                <input type="text" name="username" value="{{ old('username', $petugas->username) }}" class="w-full border p-2 rounded @error('username') border-red-500 bg-red-50 @enderror">
                @error('username')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>
            
            <div>
                <label class="block font-medium text-gray-700 mb-1">Email (opsional)</label>
                <input type="email" name="email" value="{{ old('email', $petugas->email) }}" class="w-full border p-2 rounded @error('email') border-red-500 bg-red-50 @enderror">
                @error('email')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="block font-medium text-gray-700 mb-1">Level <span class="text-red-600">*</span></label>
            <select name="level" class="w-full border p-2 rounded @error('level') border-red-500 bg-red-50 @enderror">
                <option value="Administrasi" {{ old('level', $petugas->level) == 'Administrasi' ? 'selected' : '' }}>Administrasi</option>
                <option value="Desain" {{ old('level', $petugas->level) == 'Desain' ? 'selected' : '' }}>Desain</option>
                <option value="Produksi" {{ old('level', $petugas->level) == 'Produksi' ? 'selected' : '' }}>Produksi</option>
            </select>
            @error('level')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block font-medium text-gray-700 mb-1">Password Baru (opsional)</label>
                <input type="password" name="password" class="w-full border p-2 rounded @error('password') border-red-500 bg-red-50 @enderror">
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password.</p>
                @error('password')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>
            
            <div>
                <label class="block font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="w-full border p-2 rounded @error('password') border-red-500 bg-red-50 @enderror">
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded">Update</button>
            <a href="{{ route('petugas.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">Batal</a>
        </div>
    </form>
</div>
@endsection