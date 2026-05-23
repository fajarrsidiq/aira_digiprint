@extends('layouts.app')

@section('title', 'Jenis Pembayaran')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <div class="flex justify-between mb-4">
        <h2 class="text-xl font-bold">Data Jenis Pembayaran</h2>
        <a href="{{ route('jenispembayaran.create') }}" class="bg-green-600 text-white px-4 py-2 rounded">+ Tambah</a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2">#</th>
                    <th class="border p-2">Metode</th>
                    <th class="border p-2">No. Rekening</th>
                    <th class="border p-2">Atas Nama</th>
                    <th class="border p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jenisPembayaran as $index => $j)
                <tr>
                    <td class="border p-2">{{ $index + 1 }}</td>
                    <td class="border p-2">{{ $j->nama_metode }}</td>
                    <td class="border p-2">{{ $j->no_rekening ?? '-' }}</td>
                    <td class="border p-2">{{ $j->atas_nama ?? '-' }}</td>
                    <td class="border p-2 text-center">
                        <a href="{{ route('jenispembayaran.edit', $j->id_jenis_pembayaran) }}" class="text-yellow-600 mr-2">Edit</a>
                        <button type="button" class="text-red-600 hover:text-red-800" onclick="confirmDelete({{ $j->id_jenis_pembayaran }}, '{{ addslashes($j->nama_metode) }}')">Hapus</button>
                        <form id="delete-form-{{ $j->id_jenis_pembayaran }}" action="{{ route('jenispembayaran.destroy', $j->id_jenis_pembayaran) }}" method="POST" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $jenisPembayaran->links() }}
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id, nama) {
        Swal.fire({
            title: "Apakah Anda yakin?",
            text: `Jenis pembayaran "${nama}" akan dihapus!`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }
</script>
@endsection