@extends('layouts.app')

@section('title', 'Notifikasi Pesanan')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <div class="mb-6">
        <h2 class="text-xl font-bold">Notifikasi Pesanan Baru</h2>
        <p class="text-sm text-gray-600">Daftar pesanan pelanggan yang menunggu konfirmasi.</p>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2 w-12 text-center">No</th>
                    <th class="border p-2 text-left">No Invoice</th>
                    <th class="border p-2 text-left">Pelanggan</th>
                    <th class="border p-2 text-left">Total Bayar</th>
                    <th class="border p-2 text-left">Nominal Bayar</th>
                    <th class="border p-2 text-left">Bukti</th>
                    <th class="border p-2 text-left">Pilih Desainer</th>
                    <th class="border p-2 text-center w-48">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanans as $index => $p)
                <tr class="hover:bg-gray-50">
                    <td class="border p-2 text-center">{{ $index + 1 }}</td>
                    <td class="border p-2 font-medium">{{ $p->no_invoice }}</td>
                    <td class="border p-2">{{ $p->pelanggan->nama_pelanggan ?? '-' }}</td>
                    <td class="border p-2">Rp {{ number_format($p->total_tagihan, 0, ',', '.') }}</td>
                    <td class="border p-2 text-emerald-600 font-bold">Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                    <td class="border p-2 text-xs text-center">
                        @if($p->bukti_bayar)
                            <div class="flex flex-col gap-1 items-center">
                                <a href="{{ asset('storage/' . $p->bukti_bayar) }}" target="_blank" class="text-emerald-600 hover:underline">Lihat</a>
                                <a href="{{ asset('storage/' . $p->bukti_bayar) }}" download class="text-gray-600 hover:underline">Unduh</a>
                            </div>
                        @else
                            <span class="text-gray-400 italic">-</span>
                        @endif
                    </td>
                    <td class="border p-2">
                        <form id="form-terima-{{ $p->id_transaksi }}" action="{{ route('admin.notifikasi.proses', $p->id_transaksi) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="terima">
                            <select name="id_desainer" class="w-full border rounded p-1 text-sm" required>
                                <option value="">-- Pilih Desainer --</option>
                                @foreach($desainers as $d)
                                    <option value="{{ $d->id_petugas }}">{{ $d->nama_lengkap }}</option>
                                @endforeach
                            </select>
                    </td>
                    <td class="border p-2 text-center">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs font-bold mr-1">
                                Terima
                            </button>
                        </form>

                        <form action="{{ route('admin.notifikasi.proses', $p->id_transaksi) }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="action" value="tolak">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs font-bold">
                                Tolak
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="p-6 text-center italic">Tidak ada pesanan baru.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2",
            cancelButton: "bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded"
        },
        buttonsStyling: false
    });

    function confirmDelete(id, no_invoice) {
        swalWithBootstrapButtons.fire({
            title: "Apakah Anda yakin?",
            text: `Pesanan Invoice "${no_invoice}" akan dihapus permanen!`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }
</script>
@endsection