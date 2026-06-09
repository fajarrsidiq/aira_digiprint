@extends('layouts.app')
@section('title', 'Transaksi')
@section('content')
<div class="bg-white p-6 rounded shadow">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-xl font-bold">Daftar Transaksi</h2>
        
        {{-- Form Pencarian Data --}}
        <div class="flex flex-col sm:flex-row items-center gap-2 w-full md:w-auto justify-end">
            <form id="search-form" action="{{ route('transaksi.index') }}" method="GET" class="relative w-full sm:w-72 flex items-center">
                <input 
                    type="text" 
                    id="search-input"
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari invoice atau pelanggan..." 
                    class="w-full pl-3 pr-10 py-2 border rounded text-sm focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600"
                    autocomplete="off"
                >
                @if(request('search'))
                    <a href="{{ route('transaksi.index') }}" class="absolute right-12 text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
                <button type="submit" class="absolute right-0 top-0 bottom-0 bg-gray-100 hover:bg-gray-200 border-l border-y rounded-r px-3 text-gray-600">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </button>
            </form>

            <a href="{{ route('transaksi.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm w-full sm:w-auto text-center">+ Tambah Transaksi</a>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full border text-left">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="border p-2 text-sm font-semibold text-gray-700">No</th>
                    <th class="border p-2 text-sm font-semibold text-gray-700">Invoice</th>
                    <th class="border p-2 text-sm font-semibold text-gray-700">Pelanggan</th>
                    <th class="border p-2 text-sm font-semibold text-gray-700">Tanggal</th>
                    <th class="border p-2 text-sm font-semibold text-gray-700 text-right">Total</th>
                    <th class="border p-2 text-sm font-semibold text-gray-700 text-right">Bayar</th>
                    <th class="border p-2 text-sm font-semibold text-gray-700">Pembayaran</th>
                    <th class="border p-2 text-sm font-semibold text-gray-700 text-right">Kurang</th>
                    <th class="border p-2 text-sm font-semibold text-gray-700 text-center">Desain</th>
                    <th class="border p-2 text-sm font-semibold text-gray-700 text-center">Bukti Bayar</th>
                    <th class="border p-2 text-sm font-semibold text-gray-700 text-center">Status</th>
                    <th class="border p-2 text-sm font-semibold text-gray-700 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $index => $trx)
                @php
                    $kurangBayar = $trx->total_tagihan - $trx->jumlah_bayar;
                @endphp
                <tr class="hover:bg-gray-50 align-middle">
                    <td class="border p-2 text-sm">{{ $transaksis->firstItem() + $index }}</td>
                    <td class="border p-2 text-sm font-mono text-blue-600 font-semibold">{{ $trx->no_invoice }}</td>
                    <td class="border p-2 text-sm font-medium text-gray-900">{{ $trx->pelanggan->nama_pelanggan ?? '-' }}</td>
                    <td class="border p-2 text-sm">{{ $trx->tanggal ? $trx->tanggal->format('d/m/Y H:i') : '-' }}</td>
                    <td class="border p-2 text-sm text-right">Rp {{ number_format($trx->total_tagihan, 0, ',', '.') }}</td>
                    <td class="border p-2 text-sm text-right">Rp {{ number_format($trx->jumlah_bayar, 0, ',', '.') }}</td>
                    <td class="border p-2 text-sm">{{ $trx->pembayaran->nama_metode ?? '-' }}</td>
                    <td class="border p-2 text-sm text-right font-medium {{ $kurangBayar > 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ $kurangBayar > 0 ? 'Rp '.number_format($kurangBayar, 0, ',', '.') : 'Lunas' }}
                    </td>
                    
                    <td class="border p-2 text-xs text-center">
                        @php $desainFile = $trx->details->first()->upload_desain ?? null; @endphp
                        @if($desainFile)
                            <div class="flex flex-col gap-1 items-center">
                                <a href="{{ asset('storage/' . $desainFile) }}" target="_blank" class="text-blue-600 hover:underline">Lihat</a>
                                <a href="{{ route('transaksi.download-desain', $trx->id_transaksi) }}" class="text-gray-600 hover:underline">Unduh</a>
                            </div>
                        @else
                            <span class="text-gray-400 italic">-</span>
                        @endif
                    </td>

                    <td class="border p-2 text-xs text-center">
                        @if($trx->bukti_bayar)
                            <div class="flex flex-col gap-1 items-center">
                                <a href="{{ asset('storage/' . $trx->bukti_bayar) }}" target="_blank" class="text-emerald-600 hover:underline">Lihat</a>
                                <a href="{{ asset('storage/' . $trx->bukti_bayar) }}" download class="text-gray-600 hover:underline">Unduh</a>
                            </div>
                        @else
                            <span class="text-gray-400 italic">-</span>
                        @endif
                    </td>

                    <td class="border p-2 text-sm text-center">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($trx->status_pesanan == 'Selesai') bg-green-100 text-green-800 
                            @elseif($trx->status_pesanan == 'Dikerjakan') bg-blue-100 text-blue-800 
                            @elseif($trx->status_pesanan == 'Dibatalkan') bg-red-100 text-red-800 
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ $trx->status_pesanan }}
                        </span>
                    </td>
                    
                    <td class="border p-2 text-sm text-center" x-data="{ open: false }">
                        <div class="inline-block text-left relative">
                            <button @click="open = !open" class="bg-gray-100 hover:bg-gray-200 border text-gray-700 px-2 py-1 rounded inline-flex items-center gap-1 text-xs focus:outline-none">
                                Aksi <span class="text-[10px]">▼</span>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-32 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 flex flex-col p-1 text-left" style="display: none;">
                                <a href="{{ route('transaksi.invoice', $trx->id_transaksi) }}" class="px-3 py-1.5 text-xs text-gray-700 hover:bg-gray-100 rounded flex items-center gap-2">🖨️ Invoice</a>
                                @if($kurangBayar > 0)
                                    <a href="{{ route('transaksi.pelunasan', $trx->id_transaksi) }}" class="px-3 py-1.5 text-xs text-blue-600 hover:bg-blue-50 font-medium rounded flex items-center gap-2">✔ Lunasi</a>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="12" class="border p-8 text-center text-gray-400 italic">Tidak ada transaksi ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $transaksis->appends(request()->query())->links() }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection