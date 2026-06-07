@extends('layouts.app')
@section('title', 'Tambah Satuan')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-md mx-auto">
    <h2 class="text-xl font-bold mb-4">Tambah Satuan</h2>
    <form action="{{ route('satuan.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="block">Nama Satuan<span class="text-red-500">*</span></label>
            <input type="text" name="nama_satuan" value="{{ old('nama_satuan') }}" class="w-full border p-2 rounded @error('nama_satuan') border-red-500 bg-red-50 @enderror">

            @error('nama_satuan')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>
        <div class="flex gap-2"><button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button><a href="{{ route('satuan.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded">Batal</a></div>
    </form>
</div>
@endsection