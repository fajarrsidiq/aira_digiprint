@extends('layouts.app')
@section('title', 'Edit Pelunasan')
@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    {{-- Bagian Atas: Rincian Nota Order --}}
    <div class="bg-white border border-gray-300 rounded-lg shadow-sm p-6 text-sm text-gray-700">
        <div class="flex justify-between items-center border-b border-gray-200 pb-3 mb-4">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">Edit Pelunasan</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="space-y-2.5">
                <div class="grid grid-cols-3"><span class="text-gray-500">Invoice</span><span class="col-span-2 font-bold font-mono text-gray-900">: {{ $transaksi->no_invoice }}</span></div>
                <div class="grid grid-cols-3"><span class="text-gray-500">Kasir</span><span class="col-span-2">: {{ auth()->user()->username ?? 'Admin' }}</span></div>
                <div class="grid grid-cols-3"><span class="text-gray-500">Tanggal</span><span class="col-span-2">: {{ $transaksi->tanggal ? \Carbon\Carbon::parse($transaksi->tanggal)->format('d F Y') : ($transaksi->tanggal_transaksi ? \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d F Y') : '-') }}</span></div>
            </div>
            <div class="space-y-2.5">
                <div class="grid grid-cols-3"><span class="text-gray-500">Pelanggan</span><span class="col-span-2 font-bold text-gray-900">: {{ $transaksi->pelanggan->nama_pelanggan ?? '-' }}</span></div>
                <div class="grid grid-cols-3"><span class="text-gray-500">No Telp</span><span class="col-span-2">: {{ $transaksi->pelanggan->no_telpon ?? $transaksi->pelanggan->no_telp ?? '-' }}</span></div>
                <div class="grid grid-cols-3"><span class="text-gray-500">Alamat</span><span class="col-span-2">: {{ $transaksi->pelanggan->alamat ?? '-' }}</span></div>
            </div>
        </div>

        {{-- Tabel Item Pesanan --}}
        <div class="overflow-x-auto border border-gray-200 rounded-lg mb-4">
            <table class="min-w-full bg-white text-xs divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50 text-gray-700 font-semibold border-b">
                        <th class="p-3 border-r text-center w-12">No</th>
                        <th class="p-3 border-r text-left">Nama Produk</th>
                        <th class="p-3 border-r text-center">Ukuran</th>
                        <th class="p-3 border-r text-right">Harga</th>
                        <th class="p-3 border-r text-center w-16">Qty</th>
                        <th class="p-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 divide-y divide-gray-200">
                    @forelse(($transaksi->details ?? []) as $index => $detail)
                    @php
                        // Deteksi otomatis variabel qty / Qty / QUANTITY
                        $qty = $detail->qty ?? $detail->Qty ?? $detail->quantity ?? 0;
                        
                        // Deteksi otomatis variabel harga satuan
                        $harga = $detail->harga_satuan ?? $detail->harga ?? $detail->Harga ?? 0;
                        
                        // Deteksi otomatis variabel subtotal total item
                        $totalItem = $detail->subtotal ?? $detail->total ?? $detail->Total ?? ($harga * $qty);
                        
                        // Jika harga masih 0 tetapi subtotal ada, hitung mundur harganya
                        if ($harga == 0 && $totalItem > 0 && $qty > 0) {
                            $harga = $totalItem / $qty;
                        }
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-3 border-r text-center">{{ $index + 1 }}</td>
                        <td class="p-3 border-r font-medium text-gray-900">
                            {{ $detail->produk->nama_produk ?? 'Produk Tidak Diketahui' }}
                        </td>
                        <td class="p-3 border-r text-center text-gray-500">{{ $detail->keterangan_ukuran ?? $detail->ukuran ?? '-' }}</td>
                        <td class="p-3 border-r text-right">Rp {{ number_format($harga, 0, ',', '.') }}</td>
                        <td class="p-3 border-r text-center font-medium">{{ $qty }}</td>
                        <td class="p-3 text-right font-semibold text-gray-950">Rp {{ number_format($totalItem, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-400 italic">Tidak ada detail item produk</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
            <div>
                <p class="italic text-gray-600">Terbilang: <span class="font-medium text-gray-800"># {{ \Str::title($transaksi->total_tagihan) }} Rupiah #</span></p>
                <div class="mt-4 p-3 bg-white border rounded">
                    <span class="text-gray-500 block text-xs mb-1">- Rincian Pembayaran Sebelumnya:</span>
                    <strong class="text-gray-800">Rp {{ number_format($transaksi->jumlah_bayar, 0, ',', '.') }} ({{ strtoupper($transaksi->pembayaran->nama_metode ?? 'TUNAI') }})</strong>
                </div>
            </div>
            <div class="space-y-1 text-right max-w-md ml-auto w-full">
                <div class="flex justify-between border-b py-1"><span>Subtotal</span><span>Rp {{ number_format($transaksi->total_tagihan, 0, ',', '.') }}</span></div>
                <div class="flex justify-between font-bold text-base py-1"><span>Grand Total</span><span>Rp {{ number_format($transaksi->total_tagihan, 0, ',', '.') }}</span></div>
                <div class="flex justify-between text-gray-600 py-1"><span>DP Paid</span><span>Rp {{ number_format($transaksi->jumlah_bayar, 0, ',', '.') }}</span></div>
                @php $sisa = $transaksi->total_tagihan - $transaksi->jumlah_bayar; @endphp
                <div class="flex justify-between text-red-600 font-bold py-1"><span>Kurang</span><span>Rp {{ number_format($sisa, 0, ',', '.') }}</span></div>
            </div>
        </div>
    </div>

    {{-- Bagian Bawah: Form Edit Pembayaran Pelunasan --}}
    <div class="bg-white border rounded shadow">
        <div class="bg-gray-50 px-4 py-3 border-b flex items-center justify-between">
            <h3 class="font-bold text-gray-700 flex items-center gap-1">Edit Pembayaran : {{ $transaksi->no_invoice }}</h3>
        </div>
        
        <form action="{{ route('transaksi.proses-pelunasan', $transaksi->id_transaksi) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Invoice</label>
                        <input type="text" value="{{ $transaksi->no_invoice }}" class="w-full border p-2 rounded bg-gray-100 text-gray-500 font-mono" readonly>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">DP / Uang Muka</label>
                        <input type="text" value="Rp {{ number_format($transaksi->jumlah_bayar, 0, ',', '.') }}" class="w-full border p-2 rounded bg-gray-100 text-gray-500" readonly>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Tgl Pelunasan</label>
                        <input type="date" name="tanggal_pelunasan" value="{{ date('Y-m-d') }}" class="w-full border p-2 rounded focus:ring bg-white text-gray-800" required>
                        <p class="text-[11px] text-blue-500 italic mt-1">Tanggal Pelunasan tidak boleh mundur dan hanya boleh maju 2 days.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Total Tagihan</label>
                        <div class="w-full bg-blue-600 text-white font-bold p-3 rounded text-2xl text-center shadow-inner">
                            Rp {{ number_format($transaksi->total_tagihan, 0, ',', '.') }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-red-600 uppercase mb-1 font-bold">Bayar Pelunasan *</label>
                        <div class="relative flex items-center">
                            <span class="absolute left-3 font-bold text-gray-400">Rp</span>
                            <input type="number" name="bayar_pelunasan" value="{{ $sisa }}" max="{{ $sisa }}" min="1" class="w-full border border-blue-400 pl-10 p-2 rounded text-2xl font-bold text-gray-900 focus:ring-2 focus:ring-blue-400" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Opsi Metode Pembayaran</label>
                        <select name="id_pembayaran" class="w-full border p-2 rounded bg-white text-gray-700 focus:ring" required>
                            <option value="">-- Pilih Opsi Pembayaran --</option>
                            @foreach(\App\Models\JenisPembayaran::all() as $pay)
                                <option value="{{ $pay->id_jenis_pembayaran }}">{{ $pay->nama_metode }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Upload Bukti Pembayaran Tambahan <span class="text-gray-400">(Opsional)</span></label>
                <input type="file" name="bukti_bayar" class="w-full border p-1 rounded text-sm text-gray-500 bg-gray-50">
            </div>

            <div class="flex justify-end gap-2 border-t pt-4">
                <a href="{{ route('transaksi.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-5 py-2 rounded text-sm font-medium">Batal</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded text-sm font-medium flex items-center gap-1 shadow-md">
                    Update Pelunasan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection