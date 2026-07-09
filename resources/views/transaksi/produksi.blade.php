@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded-xl shadow">
    <h2 class="text-xl font-bold mb-4">Daftar Pesanan</h2>
    
    <div class="overflow-x-auto">
        <table class="min-w-full border text-left">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="border p-2 text-sm text-center">No</th>
                    <th class="border p-2 text-sm">Invoice</th>
                    <th class="border p-2 text-sm">Pelanggan</th>
                    <th class="border p-2 text-sm">Tanggal</th>
                    <th class="border p-2 text-sm">Desainer</th>
                    <th class="border p-2 text-sm text-center">Manajemen Desain</th>
                    @if($user->level == 'Produksi')
                        <th class="border p-2 text-sm text-center">Aksi (Status)</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $index => $trx)
                <tr class="hover:bg-gray-50">
                    <td class="border p-2 text-sm text-center">{{ $loop->iteration }}</td>
                    <td class="border p-2 text-sm font-mono text-blue-600 font-bold">{{ $trx->no_invoice }}</td>
                    <td class="border p-2 text-sm">{{ $trx->pelanggan->nama_pelanggan ?? '-' }}</td>
                    <td class="border p-2 text-sm">{{ $trx->tanggal->format('d/m/Y') }}</td>
                    <td class="border p-2 text-sm">{{ $trx->desainer->nama_lengkap ?? 'Belum Ditugaskan' }}</td>
                    
                    {{-- Kolom Manajemen Desain (Dibagi Berdasarkan Level) --}}
                    <td class="border p-2 text-xs">
                        @foreach($trx->details as $detail)
                            <div class="mb-3 p-3 border rounded-md {{ $detail->status_desain == 'Final' ? 'bg-green-50' : 'bg-gray-50' }}">
                                <p class="font-bold text-gray-800 mb-1">{{ $detail->produk->nama_produk ?? 'Item' }}</p>

                                {{-- Akses Desainer: Melihat Draft & Upload Final --}}
                                @if($user->level == 'Desain')
                                    <div class="mb-2 border-b pb-1">
                                        <span class="text-[9px] uppercase text-gray-500 font-bold">Draft:</span>
                                        <a href="{{ asset('storage/' . $detail->file_desain) }}" target="_blank" class="text-blue-600 hover:underline">Lihat</a> |
                                        <a href="{{ route('transaksi.download-desain', $detail->id_detail) }}" class="text-gray-600 hover:underline">Unduh</a>
                                    </div>
                                    @if($detail->status_desain == 'Final')
                                        <span class="text-green-600 font-bold text-[10px]">✔ Sudah Final</span>
                                    @else
                                        <form action="{{ route('transaksi.upload-final', $detail->id_detail) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <label class="text-[10px] text-gray-600 block">Upload Final:</label>
                                            <input type="file" name="file_desain_final" class="w-full text-[10px] border p-1" onchange="this.form.submit()" required>
                                        </form>
                                    @endif
                                @endif

                                {{-- Akses Produksi: Hanya Melihat & Download Final --}}
                                @if($user->level == 'Produksi')
                                    @if($detail->status_desain == 'Final')
                                        <a href="{{ asset('storage/' . $detail->file_desain_final) }}" 
                                        class="text-indigo-700 font-bold underline text-[11px]" 
                                        download>
                                        Download File Final
                                        </a>
                                    @else
                                        <span class="text-red-500 italic text-[10px]">Menunggu Desain Final...</span>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </td>

                    @if($user->level == 'Produksi')
                        <td class="border p-2 text-sm text-center">
                            <form action="{{ route('transaksi.update-status', $trx->id_transaksi) }}" method="POST">
                                @csrf @method('PUT')
                                <select name="status" onchange="this.form.submit()" class="text-xs border rounded p-1 cursor-pointer w-full">
                                    <option value="Diproses" {{ $trx->status_pesanan == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="Selesai" {{ $trx->status_pesanan == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </form>
                        </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ $user->level == 'Produksi' ? '7' : '6' }}" class="border p-4 text-center text-gray-400">
                        Tidak ada pesanan aktif.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection