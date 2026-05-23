@extends('layouts.app')

@section('title', 'Edit Jenis Pembayaran')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-4">Edit Jenis Pembayaran</h2>
    <form action="{{ route('jenispembayaran.update', $jenispembayaran->id_jenis_pembayaran) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="block font-medium mb-1">Metode Pembayaran</label>
            <input type="text" name="nama_metode" value="{{ old('nama_metode', $jenispembayaran->nama_metode) }}" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-3">
            <label class="block font-medium mb-1">No. Rekening</label>
            <input type="text" name="no_rekening" value="{{ old('no_rekening', $jenispembayaran->no_rekening) }}" class="w-full border rounded p-2">
        </div>

        <div class="mb-3">
            <label class="block font-medium mb-1">Atas Nama</label>
            <input type="text" name="atas_nama" value="{{ old('atas_nama', $jenispembayaran->atas_nama) }}" class="w-full border rounded p-2">
        </div>

        <div class="flex justify-end gap-2">
            <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded">Update</button>
            <a href="{{ route('jenispembayaran.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded">Batal</a>
        </div>
    </form>
</div>
@endsection