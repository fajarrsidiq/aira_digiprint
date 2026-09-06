@extends('layouts.app')

@section('title', 'Notifikasi Pesanan')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <div class="mb-6">
        <h2 class="text-xl font-bold">Notifikasi Pesanan Baru</h2>
        <p class="text-sm text-gray-600">Daftar pesanan pelanggan yang menunggu konfirmasi.</p>
    </div>

    <!-- Alert Notifikasi Flash Message -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full border text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2 w-12 text-center">No</th>
                    <th class="border p-2 text-left">No Invoice</th>
                    <th class="border p-2 text-left">Pelanggan</th>
                    <th class="border p-2 text-left">Produk</th>
                    <th class="border p-2 text-left">File Desain</th>
                    <th class="border p-2 text-left">Total Bayar</th>
                    <th class="border p-2 text-left">Nominal Bayar</th>
                    <th class="border p-2 text-left">Bukti Bayar</th>
                    <th class="border p-2 text-left w-48">Pilih Desainer</th>
                    <th class="border p-2 text-center w-40">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanans as $index => $p)
                <tr class="hover:bg-gray-50 align-top">
                    <td class="border p-2 text-center">{{ $index + 1 }}</td>
                    <td class="border p-2 font-medium whitespace-nowrap">{{ $p->no_invoice }}</td>
                    <td class="border p-2">{{ $p->pelanggan->nama_lengkap ?? $p->pelanggan->username ?? '-' }}</td>
                    
                    <!-- Kolom Rincian Produk -->
                    <td class="border p-2">
                        <ul class="list-disc list-inside space-y-1">
                            @forelse($p->details as $detail)
                                <li>
                                    <span class="font-semibold">{{ $detail->produk->nama_produk ?? 'Produk dihapus' }}</span>
                                    <span class="text-xs text-gray-500">({{ $detail->qty }}x - {{ $detail->keterangan_ukuran ?? '-' }})</span>
                                </li>
                            @empty
                                <span class="text-gray-400 italic">-</span>
                            @endforelse
                        </ul>
                    </td>

                    <!-- Kolom File Desain Produk -->
                    <td class="border p-2 text-xs">
                        <div class="flex flex-col gap-1">
                            @forelse($p->details as $detail)
                                @if($detail->file_desain)
                                    <div class="flex items-center gap-2">
                                        <a href="{{ asset('storage/' . $detail->file_desain) }}" target="_blank" class="text-blue-600 hover:underline font-medium">
                                            Lihat
                                        </a>
                                        <a href="{{ asset('storage/' . $detail->file_desain) }}" download class="text-gray-500 hover:underline">
                                            Unduh
                                        </a>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">Tanpa Desain</span>
                                @endif
                            @empty
                                <span class="text-gray-400 italic">-</span>
                            @endforelse
                        </div>
                    </td>

                    <td class="border p-2 whitespace-nowrap">Rp {{ number_format($p->total_tagihan, 0, ',', '.') }}</td>
                    <td class="border p-2 text-emerald-600 font-bold whitespace-nowrap">Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                    
                    <!-- Kolom Bukti Bayar Transaksi -->
                    <td class="border p-2 text-xs text-center whitespace-nowrap">
                        @if($p->bukti_bayar)
                            <div class="flex flex-col gap-1 items-center">
                                <a href="{{ asset('storage/' . $p->bukti_bayar) }}" target="_blank" class="text-emerald-600 hover:underline font-medium">Lihat</a>
                                <a href="{{ asset('storage/' . $p->bukti_bayar) }}" download class="text-gray-600 hover:underline">Unduh</a>
                            </div>
                        @else
                            <span class="text-gray-400 italic">-</span>
                        @endif
                    </td>

                    <!-- Form Terima (Input Select Desainer & Tombol Terima) -->
                    <td class="border p-2">
                        <form id="form-proses-{{ $p->id_transaksi }}" action="{{ route('admin.notifikasi.proses', $p->id_transaksi) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" id="action-{{ $p->id_transaksi }}" value="terima">
                            <select name="id_desainer" class="w-full border rounded p-1 text-sm" required>
                                <option value="">-- Pilih Desainer --</option>
                                @foreach($desainers as $d)
                                    <option value="{{ $d->id_petugas }}">{{ $d->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>

                    <!-- Kolom Aksi (Tombol Terima & Tolak) -->
                    <td class="border p-2 text-center whitespace-nowrap">
                        <div class="flex justify-center items-center gap-1">
                            <!-- Tombol Terima (Submit Form Utama) -->
                            <button type="button" onclick="submitForm('{{ $p->id_transaksi }}', 'terima')" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs font-bold">
                                Terima
                            </button>

                            <!-- Tombol Tolak (Submit Form tanpa validasi desainer) -->
                            <button type="button" onclick="submitForm('{{ $p->id_transaksi }}', 'tolak')" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs font-bold">
                                Tolak
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="p-6 text-center italic text-gray-500">Tidak ada pesanan baru.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function submitForm(id, actionType) {
        const form = document.getElementById(`form-proses-${id}`);
        const actionInput = document.getElementById(`action-${id}`);
        const selectDesainer = form.querySelector('select[name="id_desainer"]');
        
        actionInput.value = actionType;

        if (actionType === 'terima') {
            if (!selectDesainer.value) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Silakan pilih desainer terlebih dahulu!',
                    confirmButtonText: 'OK'
                });
                selectDesainer.focus();
                return;
            }
            form.submit();
        } else if (actionType === 'tolak') {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Pesanan ini akan ditolak dan dicatat dalam sistem.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, Tolak!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    selectDesainer.removeAttribute('required'); // Lepas validasi required desainer
                    form.submit();
                }
            });
        }
    }
</script>
@endsection