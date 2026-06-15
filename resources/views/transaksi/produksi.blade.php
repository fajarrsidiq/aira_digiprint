@extends('layouts.app')
@section('content')
<div class="p-6 bg-white rounded-xl shadow">
    <h2 class="text-xl font-bold mb-4">Daftar Pesanan</h2>
    
    <div class="overflow-x-auto">
        <table class="min-w-full border text-left">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="border p-2 text-sm">No</th>
                    <th class="border p-2 text-sm">Invoice</th>
                    <th class="border p-2 text-sm">Pelanggan</th>
                    <th class="border p-2 text-sm">Tanggal</th>
                    <th class="border p-2 text-sm">Desainer</th> <th class="border p-2 text-sm text-center">Desain</th>
                    
                    @if($user->level == 'Produksi')
                        <th class="border p-2 text-sm text-center">Aksi (Status)</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $index => $trx)
                <tr class="hover:bg-gray-50">
                    <td class="border p-2 text-sm">{{ $loop->iteration }}</td>
                    <td class="border p-2 text-sm font-mono text-blue-600">{{ $trx->no_invoice }}</td>
                    <td class="border p-2 text-sm">{{ $trx->pelanggan->nama_pelanggan ?? '-' }}</td>
                    <td class="border p-2 text-sm">{{ $trx->tanggal->format('d/m/Y') }}</td>
                    
                    <td class="border p-2 text-sm">
                        {{ $trx->desainer->nama_lengkap ?? 'Belum Ditugaskan' }}
                    </td>
                    
                    <td class="border p-2 text-xs text-center">
                        @foreach($trx->details as $detail)
                            @if($detail->file_desain)
                                <a href="{{ asset('storage/' . $detail->file_desain) }}" target="_blank" class="text-blue-600 hover:underline">Lihat</a> |
                                <a href="{{ route('transaksi.download-desain', $detail->id_detail) }}" class="text-gray-600 hover:underline">Unduh</a>
                            @endif
                        @endforeach
                    </td>

                    @if($user->level == 'Produksi')
                        <td class="border p-2 text-sm text-center">
                            <form action="{{ route('transaksi.update-status', $trx->id_transaksi) }}" method="POST">
                                @csrf @method('PUT')
                                <select name="status" onchange="this.form.submit()" class="text-xs border rounded p-1 cursor-pointer">
                                    <option value="Diproses" selected>Diproses</option>
                                    <option value="Selesai">Tandai Selesai</option>
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