@extends('layouts.app')

@section('title', 'Edit Pelanggan')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-6">Edit Pelanggan</h2>
    
    <form action="{{ route('pelanggan.update', $pelanggan->id_pelanggan) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-600">*</span></label>
            <input type="text" name="nama_pelanggan" value="{{ old('nama_pelanggan', $pelanggan->nama_pelanggan) }}" class="w-full border p-2 rounded @error('nama_pelanggan') border-red-500 bg-red-50 @enderror" autofocus>
            @error('nama_pelanggan')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium text-gray-700 mb-1">Username <span class="text-red-600">*</span></label>
            <input type="text" name="username" value="{{ old('username', $pelanggan->username) }}" class="w-full border p-2 rounded @error('username') border-red-500 bg-red-50 @enderror">
            @error('username')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium text-gray-700 mb-1">Alamat</label>
            <textarea name="alamat" rows="2" class="w-full border p-2 rounded @error('alamat') border-red-500 bg-red-50 @enderror">{{ old('alamat', $pelanggan->alamat) }}</textarea>
            @error('alamat')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium text-gray-700 mb-1">No. Telepon</label>
            <input type="text" name="no_telpon" value="{{ old('no_telpon', $pelanggan->no_telpon) }}" class="w-full border p-2 rounded @error('no_telpon') border-red-500 bg-red-50 @enderror">
            @error('no_telpon')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-6 p-3 bg-gray-50 border rounded">
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" name="reset_password" value="1" class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                <span class="text-sm text-gray-700">Reset password ke default (<strong>pelanggan123</strong>)</span>
            </label>
        </div>

        <div class="flex justify-end gap-2">
            <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded">Update</button>
            <a href="{{ route('pelanggan.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">Batal</a>
        </div>
    </form>
</div>
@endsection