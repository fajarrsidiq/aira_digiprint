@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-4">Edit Produk</h2>
    <form action="{{ route('produk.update', $produk->id_produk) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="mb-3">
            <label class="block font-medium mb-1">Nama Produk</label>
            <input type="text" name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}" class="w-full border rounded p-2" required>
        </div>

        <div class="grid grid-cols-2 gap-3 mb-3">
            <div>
                <label class="block font-medium mb-1">Bahan</label>
                <select name="id_bahan" class="w-full border rounded p-2" required>
                    @foreach($bahans as $b)
                        <option value="{{ $b->id_bahan }}" {{ $produk->id_bahan == $b->id_bahan ? 'selected' : '' }}>{{ $b->nama_bahan }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-medium mb-1">Satuan</label>
                <select name="id_satuan" class="w-full border rounded p-2" required>
                    @foreach($satuans as $s)
                        <option value="{{ $s->id_satuan }}" {{ $produk->id_satuan == $s->id_satuan ? 'selected' : '' }}>{{ $s->nama_satuan }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3 mb-3">
            <div>
                <label class="block font-medium mb-1">Ukuran Default</label>
                <input type="text" name="ukuran_default" value="{{ old('ukuran_default', $produk->ukuran_default) }}" class="w-full border rounded p-2">
            </div>
            <div>
                <label class="block font-medium mb-1">Harga</label>
                <input type="number" name="harga" value="{{ old('harga', $produk->harga) }}" class="w-full border rounded p-2" step="0.01" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="block font-medium mb-1">Foto Produk</label>
            @if($produk->foto_produk)
                <div class="mb-2">
                    <img src="{{ Storage::url($produk->foto_produk) }}" class="w-20 h-20 object-cover rounded">
                </div>
            @endif
            <input type="file" name="foto_produk" class="w-full border rounded p-2" accept="image/*">
            <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah foto.</p>
        </div>

        <div class="flex justify-end gap-2">
            <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded">Update</button>
            <a href="{{ route('produk.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded">Batal</a>
        </div>
    </form>
</div>
@endsection