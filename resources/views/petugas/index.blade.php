@extends('layouts.app')

@section('title', 'Petugas')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <div class="flex justify-between mb-4">
        <h2 class="text-xl font-bold">Data Petugas</h2>
        <a href="{{ route('petugas.create') }}" class="bg-green-600 text-white px-4 py-2 rounded">+ Tambah Petugas</a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2">#</th>
                    <th class="border p-2">Nama</th>
                    <th class="border p-2">Username</th>
                    <th class="border p-2">Email</th>
                    <th class="border p-2">Level</th>
                    <th class="border p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($petugas as $index => $p)
                <tr>
                    <td class="border p-2">{{ $index + 1 }}</td>
                    <td class="border p-2">{{ $p->nama_lengkap }}</td>
                    <td class="border p-2">{{ $p->username ?? '-' }}</td>
                    <td class="border p-2">{{ $p->email ?? '-' }}</td>
                    <td class="border p-2">{{ $p->level ?? '-' }}</td>
                    <td class="border p-2 text-center">
                        @if($p->level !== 'Owner')
                            <a href="{{ route('petugas.edit', $p->id_petugas) }}" class="text-yellow-600 mr-2">Edit</a>
                            <button type="button" class="text-red-600 hover:text-red-800" onclick="confirmDelete({{ $p->id_petugas }}, '{{ addslashes($p->nama_lengkap) }}')">Hapus</button>
                            <form id="delete-form-{{ $p->id_petugas }}" action="{{ route('petugas.destroy', $p->id_petugas) }}" method="POST" style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        @else
                            <span class="text-gray-400">Owner</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $petugas->links() }}
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

    function confirmDelete(id, nama) {
        swalWithBootstrapButtons.fire({
            title: "Apakah Anda yakin?",
            text: `Data produk "${nama}" akan dihapus secara permanen!`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                    title: "Dibatalkan",
                    text: "Data Anda aman :)",
                    icon: "error"
                });
            }
        });
    }
</script>
@endsection