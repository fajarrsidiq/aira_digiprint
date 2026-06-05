@extends('layouts.app')

@section('title', 'Jenis Pembayaran')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
        <h2 class="text-xl font-bold w-full md:w-auto">Data Jenis Pembayaran</h2>
        
        <div class="flex flex-col sm:flex-row items-center gap-2 w-full md:w-auto justify-end">
            <form id="search-form" action="{{ route('jenispembayaran.index') }}" method="GET" class="relative w-full sm:w-64 flex items-center">
                <input 
                    type="text" 
                    id="search-input"
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari metode pembayaran..." 
                    class="w-full pl-3 pr-10 py-2 border rounded text-sm focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600"
                    autocomplete="off"
                    autofocus
                >
                @if(request('search'))
                    <a href="{{ route('jenispembayaran.index') }}" class="absolute right-12 text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
                <button type="submit" class="absolute right-0 top-0 bottom-0 bg-gray-100 hover:bg-gray-200 border-l border-y rounded-r px-3 text-gray-600">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </button>
            </form>

            <a href="{{ route('jenispembayaran.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm w-full sm:w-auto text-center">+ Tambah</a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2 w-12 text-center">#</th>
                    <th class="border p-2 text-left">Metode</th>
                    <th class="border p-2 text-left">No. Rekening</th>
                    <th class="border p-2 text-left">Atas Nama</th>
                    <th class="border p-2 text-center w-40">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jenisPembayaran as $index => $j)
                <tr>
                    <td class="border p-2 text-center">{{ $jenisPembayaran->firstItem() + $index }}</td>
                    <td class="border p-2">{{ $j->nama_metode }}</td>
                    <td class="border p-2">{{ $j->no_rekening ?? '-' }}</td>
                    <td class="border p-2">{{ $j->atas_nama ?? '-' }}</td>
                    <td class="border p-2 text-center">
                        <a href="{{ route('jenispembayaran.edit', $j->id_jenis_pembayaran) }}" class="text-yellow-600 mr-2 hover:underline">Edit</a>
                        <button type="button" class="text-red-600 hover:text-red-800 hover:underline" onclick="confirmDelete({{ $j->id_jenis_pembayaran }}, '{{ addslashes($j->nama_metode) }}')">Hapus</button>
                        <form id="delete-form-{{ $j->id_jenis_pembayaran }}" action="{{ route('jenispembayaran.destroy', $j->id_jenis_pembayaran) }}" method="POST" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-4 border text-center text-gray-500 italic">Data jenis pembayaran tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $jenisPembayaran->appends(['search' => request('search')])->links() }}
    </div>
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

    // Script Live Search Otomatis (Submit setelah berhenti mengetik 500ms)
    const searchInput = document.getElementById('search-input');
    const searchForm = document.getElementById('search-form');
    let typingTimer;

    if (searchInput) {
        // Jaga agar kursor tetap di akhir teks setelah reload/otomatis mengetik
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