@extends('layouts.app')

@section('title', 'Edit Jenis Pembayaran')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-6">Edit Jenis Pembayaran</h2>
    
    <form action="{{ route('jenispembayaran.update', $jenispembayaran->id_jenis_pembayaran) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-medium text-gray-700 mb-1">Metode Pembayaran <span class="text-red-600">*</span></label>
            <input type="text" name="nama_metode" value="{{ old('nama_metode', $jenispembayaran->nama_metode) }}" class="w-full border p-2 rounded @error('nama_metode') border-red-500 bg-red-50 @enderror" autofocus>
            @error('nama_metode')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium text-gray-700 mb-1">No. Rekening <span class="text-xs text-gray-400">(opsional)</span></label>
            <input type="text" name="no_rekening" value="{{ old('no_rekening', $jenispembayaran->no_rekening) }}" class="w-full border p-2 rounded @error('no_rekening') border-red-500 bg-red-50 @enderror">
            @error('no_rekening')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium text-gray-700 mb-1">Atas Nama <span class="text-xs text-gray-400">(opsional)</span></label>
            <input type="text" name="atas_nama" value="{{ old('atas_nama', $jenispembayaran->atas_nama) }}" class="w-full border p-2 rounded @error('atas_nama') border-red-500 bg-red-50 @enderror">
            @error('atas_nama')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex justify-end gap-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Update</button>
            <a href="{{ route('jenispembayaran.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">Batal</a>
        </div>
    </form>
</div>
@endsection