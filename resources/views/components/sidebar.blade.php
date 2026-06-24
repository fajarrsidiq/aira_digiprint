<aside class="w-72 bg-white/70 backdrop-blur-sm shadow-xl border-r border-gray-200/60 flex-shrink-0 overflow-y-auto">
    <nav class="p-5 space-y-1">
        <div class="mb-6 pb-2 border-b border-gray-200">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Navigasi Utama</p>
        </div>

        @if(Auth::guard('petugas')->check())
            @php $userLevel = Auth::guard('petugas')->user()->level; @endphp

            <a href="{{ route('dashboard.petugas') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('dashboard.petugas') ? 'bg-gradient-to-r from-red-50 to-red-50 text-red-700 shadow-sm border-l-4 border-red-500' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-tachometer-alt w-5"></i> Dashboard
            </a>

            @if(in_array($userLevel, ['Owner','Administrasi']))
                <div class="pt-4 mt-4 border-t border-gray-200">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-4 mb-2">Master Data</p>
                </div>

                <a href="{{ route('satuan.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('satuan.*') ? 'bg-gradient-to-r from-red-50 to-red-50 text-red-700 shadow-sm border-l-4 border-red-500' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-balance-scale w-5"></i> Satuan
                </a>

                <a href="{{ route('bahan.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('bahan.*') ? 'bg-gradient-to-r from-red-50 to-red-50 text-red-700 shadow-sm border-l-4 border-red-500' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-cubes w-5"></i> Bahan
                </a>

                <a href="{{ route('produk.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('produk.*') ? 'bg-gradient-to-r from-red-50 to-red-50 text-red-700 shadow-sm border-l-4 border-red-500' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-boxes w-5"></i> Produk
                </a>

                <a href="{{ route('pelanggan.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('pelanggan.*') ? 'bg-gradient-to-r from-red-50 to-red-50 text-red-700 shadow-sm border-l-4 border-red-500' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-users w-5"></i> Pelanggan
                </a>
            @endif

            @if($userLevel === 'Owner')
                <a href="{{ route('petugas.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('petugas.*') ? 'bg-gradient-to-r from-red-50 to-red-50 text-red-700 shadow-sm border-l-4 border-red-500' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-user-tie w-5"></i> Petugas
                </a>

                <a href="{{ route('jenispembayaran.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('jenispembayaran.*') ? 'bg-gradient-to-r from-red-50 to-red-50 text-red-700 shadow-sm border-l-4 border-red-500' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-credit-card w-5"></i> Jenis Pembayaran
                </a>
            @endif

            @if(in_array($userLevel, ['Owner','Administrasi']))
                <div class="pt-4 mt-4 border-t border-gray-200">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-4 mb-2">Transaksi</p>
                </div>
                <a href="{{ route('transaksi.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('transaksi.*') ? 'bg-gradient-to-r from-red-50 to-red-50 text-red-700 shadow-sm border-l-4 border-red-500' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-shopping-cart w-5"></i> Transaksi
                </a>
            @endif

            @if($userLevel === 'Owner')
                <div class="pt-4 mt-4 border-t border-gray-200">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-4 mb-2">Laporan</p>
                </div>
                <a href="{{ route('laporan.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('laporan.*') ? 'bg-gradient-to-r from-red-50 to-red-50 text-red-700 shadow-sm border-l-4 border-red-500' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-chart-pie w-5"></i> Laporan
                </a>
            @endif
            
            @if(in_array($userLevel, ['Desain','Produksi']))
                <a href="{{ route('transaksi.produksi') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('transaksi.produksi') ? 'bg-gradient-to-r from-red-50 to-red-50 text-red-700 shadow-sm border-l-4 border-red-500' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-tasks w-5"></i> Daftar Pesanan
                </a>
            @endif

        @elseif(Auth::guard('pelanggan')->check())
            <a href="{{ route('dashboard.pelanggan') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('dashboard.pelanggan') ? 'bg-gradient-to-r from-red-50 to-red-50 text-red-700 shadow-sm border-l-4 border-red-500' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-tachometer-alt w-5"></i> Dashboard
            </a>
            <a href="{{ route('pelanggan.pesanan') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('pelanggan.pesanan') ? 'bg-gradient-to-r from-red-50 to-red-50 text-red-700 shadow-sm border-l-4 border-red-500' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-shopping-cart w-5"></i> Input Pesanan
            </a>
            <a href="{{ route('pelanggan.riwayat') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('pelanggan.riwayat') ? 'bg-gradient-to-r from-red-50 to-red-50 text-red-700 shadow-sm border-l-4 border-red-500' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-receipt w-5"></i> Riwayat Transaksi
            </a>
        @endif
    </nav>
</aside>