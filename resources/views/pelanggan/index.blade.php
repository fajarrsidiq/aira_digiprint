@extends('layouts.app')

@section('title', 'Pelanggan')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <div class="flex justify-between mb-4">
        <h2 class="text-xl font-bold">Data Pelanggan</h2>
        <a href="{{ route('pelanggan.create') }}" class="bg-green-600 text-white px-4 py-2 rounded">+ Tambah Pelanggan</a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2">#</th>
                    <th class="border p-2">Username</th>
                    <th class="border p-2">Alamat</th>
                    <th class="border p-2">No Telepon</th>
                    <th class="border p-2">Dibuat</th>
                    <th class="border p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pelanggans as $index => $p)
                <tr>
                    <td class="border p-2">{{ $index + 1 }}</td>
                    <td class="border p-2">{{ $p->username }}</td>
                    <td class="border p-2">{{ $p->alamat ?? '-' }}</td>
                    <td class="border p-2">{{ $p->no_telpon ?? '-' }}</td>
                    <td class="border p-2">{{ $p->created_at->format('d/m/Y') }}</td>
                    <td class="border p-2 text-center">
                        <a href="{{ route('pelanggan.edit', $p->id_pelanggan) }}" class="text-yellow-600 mr-2">Edit</a>
                        <button type="button" class="text-red-600 hover:text-red-800" onclick="confirmDelete({{ $p->id_pelanggan }}, '{{ $p->nama_pelanggan }}')">Hapus</button>
                        <form id="delete-form-{{ $p->id_pelanggan }}" action="{{ route('pelanggan.destroy', $p->id_pelanggan) }}" method="POST" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $pelanggans->links() }}
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