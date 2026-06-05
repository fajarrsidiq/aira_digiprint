@extends('layouts.app')

@section('title', 'Edit Pelanggan')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-4">Edit Pelanggan</h2>
    <form action="{{ route('pelanggan.update', $pelanggan->id_pelanggan) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="block font-medium mb-1">Nama Lengkap <span class="text-red-600">*</span></label>
            <input type="text" name="nama_pelanggan" value="{{ old('nama_pelanggan', $pelanggan->nama_pelanggan) }}" class="w-full border rounded p-2" required autofocus>
        </div>

        <div class="mb-3">
            <label class="block font-medium mb-1">Username <span class="text-red-600">*</span></label>
            <input type="text" name="username" value="{{ old('username', $pelanggan->username) }}" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-3">
            <label class="block font-medium mb-1">Alamat</label>
            <textarea name="alamat" rows="2" class="w-full border rounded p-2">{{ old('alamat', $pelanggan->alamat) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="block font-medium mb-1">No. Telepon <span class="text-red-600">*</span></label>
            <input type="text" name="no_telpon" value="{{ old('no_telpon', $pelanggan->no_telpon) }}" class="w-full border rounded p-2">
        </div>

       <div class="mb-3">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="reset_password" value="1">
                <span class="text-sm">Reset password ke default (<strong>pelanggan123</strong>)</span>
            </label>
        </div>

        <div class="flex justify-end gap-2">
            <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded">Update</button>
            <a href="{{ route('pelanggan.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded">Batal</a>
        </div>
    </form>
</div>
@endsection