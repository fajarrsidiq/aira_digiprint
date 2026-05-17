<header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-200/50">
    <div class="container mx-auto px-4 lg:px-6 py-3 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-2 rounded-xl shadow-md">
                <i class="fas fa-print text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold bg-gradient-to-r from-blue-800 to-indigo-800 bg-clip-text text-transparent">AIRA Digiprint</h1>
                <p class="text-xs text-gray-500 -mt-0.5">Solusi Percetakan Digital</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            @auth
                @if(Auth::guard('petugas')->check())
                    <div class="hidden md:flex items-center gap-2 bg-gray-100 px-3 py-1.5 rounded-full">
                        <i class="fas fa-user-circle text-blue-600 text-lg"></i>
                        <span class="text-sm font-medium text-gray-700">{{ Auth::guard('petugas')->user()->nama_lengkap }}</span>
                        <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">{{ Auth::guard('petugas')->user()->level }}</span>
                    </div>
                @else
                    <div class="hidden md:flex items-center gap-2 bg-gray-100 px-3 py-1.5 rounded-full">
                        <i class="fas fa-user-circle text-blue-600 text-lg"></i>
                        <span class="text-sm font-medium text-gray-700">{{ Auth::guard('pelanggan')->user()->username }}</span>
                    </div>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 bg-red-500/90 hover:bg-red-600 text-white px-3 py-1.5 rounded-full text-sm transition">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </button>
                </form>
            @endauth
        </div>
    </div>
</header>