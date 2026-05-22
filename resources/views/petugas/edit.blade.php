@extends('layouts.app')

@section('title', 'Edit Petugas')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-4">Edit Petugas</h2>
    <form action="{{ route('petugas.update', $petugas->id_petugas) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="block font-medium mb-1">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $petugas->nama_lengkap) }}" class="w-full border rounded p-2" required>
        </div>

        <div class="grid grid-cols-2 gap-3 mb-3">
            <div>
                <label class="block font-medium mb-1">Username</label>
                <input type="text" name="username" value="{{ old('username', $petugas->username) }}" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block font-medium mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $petugas->email) }}" class="w-full border rounded p-2">
            </div>
        </div>

        <div class="mb-3">
            <label class="block font-medium mb-1">Level</label>
            <select name="level" class="w-full border rounded p-2" required>
                <option value="Administrasi" {{ $petugas->level == 'Administrasi' ? 'selected' : '' }}>Administrasi</option>
                <option value="Desain" {{ $petugas->level == 'Desain' ? 'selected' : '' }}>Desain</option>
                <option value="Produksi" {{ $petugas->level == 'Produksi' ? 'selected' : '' }}>Produksi</option>
            </select>
        </div>

        <div class="grid grid-cols-2 gap-3 mb-3">
            <div>
                <label class="block font-medium mb-1">Password Baru (opsional)</label>
                <input type="password" name="password" class="w-full border rounded p-2">
                <p class="text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password.</p>
            </div>
            <div>
                <label class="block font-medium mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="w-full border rounded p-2">
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded">Update</button>
            <a href="{{ route('petugas.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded">Batal</a>
        </div>
    </form>
</div>
@endsection