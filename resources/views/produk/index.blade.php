@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <div class="flex justify-between mb-4">
        <h2 class="text-xl font-bold">Data Produk</h2>
        <a href="{{ route('produk.create') }}" class="bg-green-600 text-white px-4 py-2 rounded">+ Tambah Produk</a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2">#</th>
                    <th class="border p-2">Nama Produk</th>
                    <th class="border p-2">Bahan</th>
                    <th class="border p-2">Satuan</th>
                    <th class="border p-2">Ukuran</th>
                    <th class="border p-2">Harga</th>
                    <th class="border p-2">Foto</th>
                    <th class="border p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produks as $index => $p)
                <tr>
                    <td class="border p-2">{{ $index + 1 }}</td>
                    <td class="border p-2">{{ $p->nama_produk }}</td>
                    <td class="border p-2">{{ $p->bahan->nama_bahan ?? '-' }}</td>
                    <td class="border p-2">{{ $p->satuan->nama_satuan ?? '-' }}</td>
                    <td class="border p-2">{{ $p->ukuran_default ?? '-' }}</td>
                    <td class="border p-2">Rp {{ number_format($p->harga,0,',','.') }}</td>
                    <td class="border p-2 text-center">
                        @if($p->foto_produk)
                            <img src="{{ Storage::url($p->foto_produk) }}" class="w-10 h-10 object-cover rounded">
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="border p-2 text-center">
                        <a href="{{ route('produk.edit', $p->id_produk) }}" class="text-yellow-600 mr-2">Edit</a>
                        <button type="button" class="text-red-600 hover:text-red-800" onclick="confirmDelete({{ $p->id_produk }}, '{{ $p->nama_produk }}')">Hapus</button>
                        <form id="delete-form-{{ $p->id_produk }}" action="{{ route('produk.destroy', $p->id_produk) }}" method="POST" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $produks->links() }}
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