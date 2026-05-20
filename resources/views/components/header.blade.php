<header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-200/50">
    <div class="container mx-auto px-4 lg:px-6 py-3 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-2 rounded-xl shadow-md"><i class="fas fa-print text-white text-xl"></i></div>
            <div><h1 class="text-xl font-bold bg-gradient-to-r from-blue-800 to-indigo-800 bg-clip-text text-transparent">AIRA Digiprint</h1><p class="text-xs text-gray-500 -mt-0.5">Solusi Percetakan Digital</p></div>
        </div>
        <div class="flex items-center gap-4">
            @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 bg-gray-100 px-3 py-1.5 rounded-full hover:bg-gray-200 transition">
                        <i class="fas fa-user-circle text-blue-600 text-lg"></i>
                        @if(Auth::guard('petugas')->check())
                            <span class="text-sm font-medium text-gray-700">{{ Auth::guard('petugas')->user()->nama_lengkap }}</span>
                            <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">{{ Auth::guard('petugas')->user()->level }}</span>
                        @else
                            <span class="text-sm font-medium text-gray-700">{{ Auth::guard('pelanggan')->user()->username }}</span>
                        @endif
                        <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"><i class="fas fa-user mr-2"></i> Profil Saya</a>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"><i class="fas fa-key mr-2"></i> Ubah Password</a>
                        <hr class="my-1">
                        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100"><i class="fas fa-sign-out-alt mr-2"></i> Keluar</button></form>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</header>