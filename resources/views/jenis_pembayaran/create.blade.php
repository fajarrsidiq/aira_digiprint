@extends('layouts.app')

@section('title', 'Tambah Jenis Pembayaran')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-4">Tambah Jenis Pembayaran Baru</h2>
    <form action="{{ route('jenispembayaran.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="block font-medium mb-1">Metode Pembayaran</label>
            <input type="text" name="nama_metode" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-3">
            <label class="block font-medium mb-1">No. Rekening (opsional)</label>
            <input type="text" name="no_rekening" class="w-full border rounded p-2">
        </div>

        <div class="mb-3">
            <label class="block font-medium mb-1">Atas Nama (opsional)</label>
            <input type="text" name="atas_nama" class="w-full border rounded p-2">
        </div>

        <div class="flex justify-end gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
            <a href="{{ route('jenispembayaran.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded">Batal</a>
        </div>
    </form>
</div>
@endsection