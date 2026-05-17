<aside class="w-72 bg-white/70 backdrop-blur-sm shadow-xl border-r border-gray-200/60 flex-shrink-0 overflow-y-auto">
    <nav class="p-5 space-y-1">
        <div class="mb-6 pb-2 border-b border-gray-200">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Navigasi Utama</p>
        </div>

        @if(Auth::guard('petugas')->check())
            @php $userLevel = Auth::guard('petugas')->user()->level; @endphp

            <a href="{{ route('dashboard.petugas') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('dashboard.petugas') ? 'bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 shadow-sm border-l-4 border-blue-500' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-tachometer-alt w-5"></i> Dashboard
            </a>

            @if(in_array($userLevel, ['Owner','Administrasi']))
                <div class="pt-4 mt-4 border-t border-gray-200">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-4 mb-2">Master Data</p>
                </div>

                <a href="{{ route('satuan.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('satuan.*') ? 'bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 shadow-sm border-l-4 border-blue-500' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-balance-scale w-5"></i> Satuan
                </a>

                <!-- Tambahkan menu lain (bahan, produk, pelanggan) di sini dengan pola sama -->
            @endif

            @if($userLevel === 'Owner')
                <a href="#" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all">
                    <i class="fas fa-credit-card w-5"></i> Jenis Pembayaran
                </a>
            @endif

            @if(in_array($userLevel, ['Owner','Administrasi']))
                <div class="pt-4 mt-4 border-t border-gray-200">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-4 mb-2">Transaksi</p>
                </div>
                <a href="#" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all">
                    <i class="fas fa-shopping-cart w-5"></i> Riwayat Transaksi
                </a>
            @endif

        @elseif(Auth::guard('pelanggan')->check())
            <a href="{{ route('dashboard.pelanggan') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all">
                <i class="fas fa-tachometer-alt w-5"></i> Dashboard
            </a>
            <a href="#" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all">
                <i class="fas fa-shopping-cart w-5"></i> Pesanan Saya
            </a>
        @endif
    </nav>
</aside>