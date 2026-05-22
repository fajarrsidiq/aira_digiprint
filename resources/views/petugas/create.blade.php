@extends('layouts.app')

@section('title', 'Tambah Petugas')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-4">Tambah Petugas Baru</h2>
    <form action="{{ route('petugas.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="block font-medium mb-1">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="w-full border rounded p-2" required>
        </div>

        <div class="grid grid-cols-2 gap-3 mb-3">
            <div>
                <label class="block font-medium mb-1">Username</label>
                <input type="text" name="username" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block font-medium mb-1">Email (opsional)</label>
                <input type="email" name="email" class="w-full border rounded p-2">
            </div>
        </div>

        <div class="mb-3">
            <label class="block font-medium mb-1">Level</label>
            <select name="level" class="w-full border rounded p-2" required>
                <option value="Administrasi">Administrasi</option>
                <option value="Desain">Desain</option>
                <option value="Produksi">Produksi</option>
            </select>
        </div>

        <div class="grid grid-cols-2 gap-3 mb-3">
            <div>
                <label class="block font-medium mb-1">Password</label>
                <input type="password" name="password" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block font-medium mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="w-full border rounded p-2" required>
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
            <a href="{{ route('petugas.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded">Batal</a>
        </div>
    </form>
</div>
@endsection