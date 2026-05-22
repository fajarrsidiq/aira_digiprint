@extends('layouts.app')

@section('title', 'Tambah Pelanggan')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-4">Tambah Pelanggan Baru</h2>
    <form action="{{ route('pelanggan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="block font-medium mb-1">Username <span class="text-red-600">*</span></label>
            <input type="text" name="username" class="w-full border rounded p-2" required>
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
@endsection