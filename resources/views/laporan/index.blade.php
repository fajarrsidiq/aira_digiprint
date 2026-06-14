@extends('layouts.app')
@section('title', 'Laporan Transaksi')

@section('content')
<div class="space-y-6">
    <h2 class="text-2xl font-bold text-gray-800">Laporan Transaksi</h2>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Filter Laporan</h3>
            <form action="{{ route('laporan.index') }}" method="GET" class="flex flex-col gap-4">
                
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block text-sm text-gray-600 mb-1">Dari Tanggal</label>
                        <input type="date" name="tgl_awal" value="{{ request('tgl_awal') }}" class="border rounded-lg p-2 w-full">
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm text-gray-600 mb-1">Sampai Tanggal</label>
                        <input type="date" name="tgl_akhir" value="{{ request('tgl_akhir') }}" class="border rounded-lg p-2 w-full">
                    </div>
                </div>

                <div class="flex flex-col gap-2 mt-2">
                    <button type="submit" class="w-full bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium">
                        Tampilkan Data
                    </button>
                    <a href="{{ route('laporan.export', request()->all()) }}" class="w-full text-center bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-file-excel"></i> Export ke Excel
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Rekap Kas Masuk</h3>
            <div class="space-y-3">
                @forelse($rekapPembayaran as $namaMetode => $total)
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-600 font-medium">{{ $namaMetode }}:</span>
                        <span class="font-bold text-gray-800">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 italic">Data tidak ditemukan</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Detail Transaksi</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">No</th>
                        <th class="px-4 py-2 text-left">Invoice</th>
                        <th class="px-4 py-2 text-left">Pelanggan</th>
                        <th class="px-4 py-2 text-left">Tanggal</th>
                        <th class="px-4 py-2 text-right">Total</th>
                        <th class="px-4 py-2 text-right">Bayar</th>
                        <th class="px-4 py-2 text-left">Metode</th>
                        <th class="px-4 py-2 text-right">Kurang</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $trx)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 font-medium">{{ $trx->no_invoice }}</td>
                        <td class="px-4 py-2">{{ $trx->pelanggan->username ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $trx->tanggal->format('d/m/Y') }}</td>
                        <td class="px-4 py-2 text-right">Rp {{ number_format($trx->total_tagihan, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-right text-green-600 font-bold">Rp {{ number_format($trx->jumlah_bayar, 0, ',', '.') }}</td>
                        {{-- Menggunakan relasi pembayaran --}}
                        <td class="px-4 py-2">{{ $trx->pembayaran->nama_metode ?? '-' }}</td>
                        <td class="px-4 py-2 text-right text-red-600 font-bold">
                            Rp {{ number_format(max(0, $trx->total_tagihan - $trx->jumlah_bayar), 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-6 text-gray-500">Data tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-100 font-bold">
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-right">TOTAL KESELURUHAN</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right text-green-600">Rp {{ number_format($totalBayar, 0, ',', '.') }}</td>
                        <td></td>
                        <td class="px-4 py-3 text-right text-red-600">Rp {{ number_format($totalKurang, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection