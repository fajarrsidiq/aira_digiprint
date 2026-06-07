@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-6">Tambah Produk Baru</h2>
    
    <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf

        <div class="mb-4">
            <label class="block font-medium text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
            <input type="text" name="nama_produk" value="{{ old('nama_produk') }}" class="w-full border p-2 rounded @error('nama_produk') border-red-500 bg-red-50 @enderror">
            @error('nama_produk')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block font-medium text-gray-700 mb-1">Bahan <span class="text-red-500">*</span></label>
                <select name="id_bahan" class="w-full border p-2 rounded @error('id_bahan') border-red-500 bg-red-50 @enderror">
                    <option value="">Pilih Bahan</option>
                    @foreach($bahans as $b)
                        <option value="{{ $b->id_bahan }}" {{ old('id_bahan') == $b->id_bahan ? 'selected' : '' }}>
                            {{ $b->nama_bahan }}
                        </option>
                    @endforeach
                </select>
                @error('id_bahan')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>
            
            <div>
                <label class="block font-medium text-gray-700 mb-1">Satuan <span class="text-red-500">*</span></label>
                <select name="id_satuan" class="w-full border p-2 rounded @error('id_satuan') border-red-500 bg-red-50 @enderror">
                    <option value="">Pilih Satuan</option>
                    @foreach($satuans as $s)
                        <option value="{{ $s->id_satuan }}" {{ old('id_satuan') == $s->id_satuan ? 'selected' : '' }}>
                            {{ $s->nama_satuan }}
                        </option>
                    @endforeach
                </select>
                @error('id_satuan')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block font-medium text-gray-700 mb-1">Ukuran Default</label>
                <input type="text" name="ukuran_default" value="{{ old('ukuran_default') }}" placeholder="Contoh: A4, 10x15" class="w-full border p-2 rounded @error('ukuran_default') border-red-500 bg-red-50 @enderror">
                @error('ukuran_default')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>
            
            <div>
                <label class="block font-medium text-gray-700 mb-1">Harga <span class="text-red-500">*</span></label>
                <input type="number" name="harga"  value="{{ old('harga') }}" class="w-full border p-2 rounded @error('harga') border-red-500 bg-red-50 @enderror">
                @error('harga')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="mb-6">
            <label class="block font-medium text-gray-700 mb-1">Foto Produk (opsional)</label>
            <input type="file" name="foto_produk" class="w-full border p-2 rounded @error('foto_produk')border-red-500 bg-red-50 @enderror" accept="image/*">
            @error('foto_produk')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex justify-end gap-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
            <a href="{{ route('produk.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">Batal</a>
        </div>
    </form>
</div>
@endsection