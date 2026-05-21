@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-4">Tambah Produk Baru</h2>
    <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="block font-medium mb-1">Nama Produk</label>
            <input type="text" name="nama_produk" class="w-full border rounded p-2" required>
        </div>

        <div class="grid grid-cols-2 gap-3 mb-3">
            <div>
                <label class="block font-medium mb-1">Bahan</label>
                <select name="id_bahan" class="w-full border rounded p-2" required>
                    <option value="">Pilih Bahan</option>
                    @foreach($bahans as $b)
                        <option value="{{ $b->id_bahan }}">{{ $b->nama_bahan }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-medium mb-1">Satuan</label>
                <select name="id_satuan" class="w-full border rounded p-2" required>
                    <option value="">Pilih Satuan</option>
                    @foreach($satuans as $s)
                        <option value="{{ $s->id_satuan }}">{{ $s->nama_satuan }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3 mb-3">
            <div>
                <label class="block font-medium mb-1">Ukuran Default</label>
                <input type="text" name="ukuran_default" class="w-full border rounded p-2" placeholder="Contoh: A4, 10x15">
            </div>
            <div>
                <label class="block font-medium mb-1">Harga</label>
                <input type="number" name="harga" class="w-full border rounded p-2" step="0.01" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="block font-medium mb-1">Foto Produk (opsional)</label>
            <input type="file" name="foto_produk" class="w-full border rounded p-2" accept="image/*">
        </div>

        <div class="flex justify-end gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
            <a href="{{ route('produk.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded">Batal</a>
        </div>
    </form>
</div>
@endsection