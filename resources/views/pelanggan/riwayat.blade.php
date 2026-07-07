@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6">Riwayat Pesanan Saya</h2>
    
    <div class="bg-white shadow rounded p-4 overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 text-sm">
                    <th class="p-3 border">Invoice</th>
                    <th class="p-3 border">Tanggal</th>
                    <th class="p-3 border">Total Bayar</th>
                    <th class="p-3 border">Metode Bayar</th>
                    <th class="p-3 border">Status</th>
                    <th class="p-3 border text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $t)
                <tr class="text-sm">
                    <td class="p-3 border font-semibold">{{ $t->no_invoice }}</td>
                    <td class="p-3 border">{{ \Carbon\Carbon::parse($t->tanggal)->format('d M Y') }}</td>
                    <td class="p-3 border">Rp {{ number_format($t->total_tagihan, 0, ',', '.') }}</td>
                    <td class="p-3 border">{{ $t->pembayaran->nama_metode ?? '-' }}</td>
                    <td class="p-3 border">
                        <span class="px-2 py-1 rounded text-xs font-bold 
                            {{ $t->status_pesanan == 'Selesai' ? 'bg-green-100 text-green-700' : 
                            ($t->status_pesanan == 'Ditolak' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">
                            {{ $t->status_pesanan }}
                        </span>
                    </td>
                    <td class="p-3 border text-center">
                        @if($t->status_pesanan != 'Menunggu Konfirmasi' && $t->status_pesanan != 'Ditolak')
                            <a href="{{ route('pelanggan.invoice', $t->id_transaksi) }}" target="_blank" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-xs font-bold transition">
                            Lihat Invoice
                            </a>
                        @elseif($t->status_pesanan == 'Ditolak')
                            <span class="text-xs text-red-500 font-bold">Ditolak</span>
                        @else
                            <span class="text-xs text-gray-400 italic">Menunggu...</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="p-6 text-center text-gray-500 italic">Belum ada riwayat pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection