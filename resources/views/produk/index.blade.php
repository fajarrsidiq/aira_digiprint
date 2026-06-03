@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
        <h2 class="text-xl font-bold w-full md:w-auto">Data Produk</h2>
        
        <div class="flex flex-col sm:flex-row items-center gap-2 w-full md:w-auto justify-end">
            <form id="search-form" action="{{ route('produk.index') }}" method="GET" class="relative w-full sm:w-64 flex items-center">
                <input 
                    type="text" 
                    id="search-input"
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari nama produk..." 
                    class="w-full pl-3 pr-10 py-2 border rounded text-sm focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600"
                    autocomplete="off"
                    autofocus
                >
                @if(request('search'))
                    <a href="{{ route('produk.index') }}" class="absolute right-12 text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
                <button type="submit" class="absolute right-0 top-0 bottom-0 bg-gray-100 hover:bg-gray-200 border-l border-y rounded-r px-3 text-gray-600">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </button>
            </form>

            <a href="{{ route('produk.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm w-full sm:w-auto text-center">+ Tambah Produk</a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2 w-12 text-center">#</th>
                    <th class="border p-2 text-left">Nama Produk</th>
                    <th class="border p-2 text-left">Bahan</th>
                    <th class="border p-2 text-left">Satuan</th>
                    <th class="border p-2 text-left">Ukuran</th>
                    <th class="border p-2 text-left">Harga</th>
                    <th class="border p-2 text-center w-20">Foto</th>
                    <th class="border p-2 text-center w-40">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produks as $index => $p)
                <tr>
                    <td class="border p-2 text-center">{{ $produks->firstItem() + $index }}</td>
                    <td class="border p-2">{{ $p->nama_produk }}</td>
                    <td class="border p-2">{{ $p->bahan->nama_bahan ?? '-' }}</td>
                    <td class="border p-2">{{ $p->satuan->nama_satuan ?? '-' }}</td>
                    <td class="border p-2">{{ $p->ukuran_default ?? '-' }}</td>
                    <td class="border p-2">Rp {{ number_format($p->harga,0,',','.') }}</td>
                    <td class="border p-2 text-center">
                        @if($p->foto_produk)
                            <img src="{{ Storage::url($p->foto_produk) }}" class="w-10 h-10 object-cover rounded mx-auto">
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="border p-2 text-center">
                        <a href="{{ route('produk.edit', $p->id_produk) }}" class="text-yellow-600 mr-2 hover:underline">Edit</a>
                        <button type="button" class="text-red-600 hover:text-red-800 hover:underline" onclick="confirmDelete({{ $p->id_produk }}, '{{ $p->nama_produk }}')">Hapus</button>
                        <form id="delete-form-{{ $p->id_produk }}" action="{{ route('produk.destroy', $p->id_produk) }}" method="POST" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="p-4 border text-center text-gray-500 italic">Data produk tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $produks->appends(['search' => request('search')])->links() }}
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

    const searchInput = document.getElementById('search-input');
    const searchForm = document.getElementById('search-form');
    let typingTimer;

    if (searchInput) {
        searchInput.focus();
        const val = searchInput.value;
        searchInput.value = '';
        searchInput.value = val;

        searchInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(function() {
                searchForm.submit();
            }, 500);
        });
    }
</script>
@endsection