@extends('layouts.app')

@section('title', 'Pelanggan')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
        <h2 class="text-xl font-bold w-full md:w-auto">Data Pelanggan</h2>
        
        <div class="flex flex-col sm:flex-row items-center gap-2 w-full md:w-auto justify-end">
            <form id="search-form" action="{{ route('pelanggan.index') }}" method="GET" class="relative w-full sm:w-64 flex items-center">
                <input 
                    type="text" 
                    id="search-input"
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari nama pelanggan..." 
                    class="w-full pl-3 pr-10 py-2 border rounded text-sm focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600"
                    autocomplete="off"
                    autofocus
                >
                @if(request('search'))
                    <a href="{{ route('pelanggan.index') }}" class="absolute right-12 text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
                <button type="submit" class="absolute right-0 top-0 bottom-0 bg-gray-100 hover:bg-gray-200 border-l border-y rounded-r px-3 text-gray-600">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </button>
            </form>

            <a href="{{ route('pelanggan.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm w-full sm:w-auto text-center">+ Tambah Pelanggan</a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2 w-12 text-center">#</th>
                    <th class="border p-2 text-left">Nama Lengkap</th>
                    <th class="border p-2 text-left">Username</th>
                    <th class="border p-2 text-left">Alamat</th>
                    <th class="border p-2 text-left">No Telepon</th>
                    <th class="border p-2 text-center w-40">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pelanggans as $index => $p)
                <tr>
                    <td class="border p-2 text-center">{{ $pelanggans->firstItem() + $index }}</td>
                    <td class="border p-2 font-medium text-gray-900">{{ $p->nama_pelanggan }}</td>
                    <td class="border p-2">{{ $p->username }}</td>
                    <td class="border p-2">{{ $p->alamat ?? '-' }}</td>
                    <td class="border p-2">{{ $p->no_telpon ?? '-' }}</td>
                    <td class="border p-2 text-center">
                        <a href="{{ route('pelanggan.edit', $p->id_pelanggan) }}" class="text-yellow-600 mr-2 hover:underline">Edit</a>
                        <button type="button" class="text-red-600 hover:text-red-800 hover:underline" onclick="confirmDelete({{ $p->id_pelanggan }}, '{{ $p->nama_pelanggan }}')">Hapus</button>
                        <form id="delete-form-{{ $p->id_pelanggan }}" action="{{ route('pelanggan.destroy', $p->id_pelanggan) }}" method="POST" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-4 border text-center text-gray-500 italic">Data pelanggan tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $pelanggans->appends(['search' => request('search')])->links() }}
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
            text: `Data pelanggan "${nama}" akan dihapus secara permanen!`,
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