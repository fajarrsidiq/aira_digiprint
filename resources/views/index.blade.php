<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIRA Advertising - Solusi Percetakan Digital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .bg-aira-red { background-color: #DC2626; }
        .text-aira-red { color: #DC2626; }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-white text-gray-900 antialiased">

    <header class="sticky top-0 bg-white/95 backdrop-blur z-50 flex justify-between items-center px-10 py-5 border-b border-gray-100 shadow-sm">
        <img src="{{ asset('images/logo-aira.png') }}" alt="Logo Aira" class="h-16 w-auto">
        <nav class="hidden md:flex gap-8 font-bold text-xs uppercase tracking-widest">
            <a href="#beranda" class="hover:text-aira-red transition">Beranda</a>
            <a href="#tentang" class="hover:text-aira-red transition">Tentang Kami</a>
            <a href="#produk" class="hover:text-aira-red transition">Produk Kami</a>
            <a href="#kontak" class="hover:text-aira-red transition">Kontak</a>
        </nav>
        <div class="flex gap-4">
            @auth
                <a href="{{ url('/dashboard') }}" class="px-5 py-2 bg-black text-white rounded-full text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="px-5 py-2 border border-black hover:bg-black hover:text-white transition text-xs font-bold uppercase tracking-widest">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="px-5 py-2 bg-aira-red text-white hover:bg-red-700 transition text-xs font-bold uppercase tracking-widest">Register</a>
                @endif
            @endauth
        </div>
    </header>

    <section id="beranda" class="flex flex-col md:flex-row items-center px-10 py-24 bg-black text-white">
        <div class="max-w-xl">
            <h1 class="text-5xl md:text-6xl font-black mb-6 leading-tight">Wujudkan Presisi di Setiap Pixel, Sempurna di Setiap Lembar</h1>
            <a href="#produk" class="inline-block px-8 py-4 bg-aira-red font-bold text-sm uppercase tracking-widest hover:bg-red-700 transition">Lihat Produk Kami</a>
        </div>
        <img src="{{ asset('images/aira.png') }}" alt="Aira" class="w-full md:w-1/2 mt-10 md:mt-0 rounded-2xl shadow-2xl">
    </section>

    <section id="tentang" class="px-10 py-24 bg-white max-w-5xl mx-auto">
        <h2 class="text-sm font-black text-aira-red uppercase tracking-[0.2em] mb-4">Profil</h2>
        <h3 class="text-4xl font-black mb-10 border-l-8 border-aira-red pl-6">Tentang Kami</h3>
        <div class="text-lg leading-relaxed space-y-6 text-gray-700">
            <p><strong>CV AIRA Advertising</strong> adalah perusahaan yang bergerak di bidang industri percetakan dan media promosi di Cianjur. Didirikan pada tahun 2005 oleh Bapak R.Andry Zaini, perusahaan ini hadir untuk memenuhi berbagai kebutuhan media cetak banner, spanduk, brosur, undangan, serta berbagai media komunikasi visual lainnya. Kami beralamat di <strong>Otista II NO. 3 Pamoyanan, Cianjur, Jawa Barat.</strong></p>
            
            <h3 class="text-2xl font-bold text-black pt-4">Visi</h3>
            <p>Menjadi perusahaan yang profesional, inovatif dan terpercaya di bidang periklanan, percetakan, serta digital printing dengan memberikan pelayanan terbaik serta hasil produk yang berkualitas unggul.</p>
            
            <h3 class="text-2xl font-bold text-black pt-4">Misi</h3>
            <ul class="list-decimal pl-6 space-y-2">
                <li>Memberikan pelayanan yang cepat, dan ramah demi menjamin kepuasan pelanggan secara maksimal.</li>
                <li>Menghasilkan produk percetakan bermutu tinggi melalui penggunaan material terbaik dan kontrol kualitas yang ketat.</li>
                <li>Mengembangkan kreativitas desain yang inovatif guna memenuhi kebutuhan komunikasi visual pelanggan yang terus berkembang.</li>
                <li>Meningkatkan kualitas kerja, efisiensi operasional, dan standar pelayanan perusahaan secara konsisten serta berkelanjutan.</li>
            </ul>
        </div>
    </section>

    <section id="produk" class="px-10 py-24 bg-gray-50">
        <h2 class="text-4xl font-black mb-16 text-center">Produk Kami</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($produks as $p)
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300">
                @if($p->foto_produk)
                    <img src="{{ asset('storage/' . $p->foto_produk) }}" class="w-full h-60 object-cover mb-4 rounded-lg" alt="{{ $p->nama_produk }}">
                @else
                    <div class="w-full h-60 bg-gray-200 flex items-center justify-center mb-4 text-gray-400 rounded-lg">Tidak ada foto</div>
                @endif
                <h3 class="font-bold text-lg uppercase">{{ $p->nama_produk }}</h3>
                <p class="text-aira-red font-bold text-xl my-2">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                <p class="text-sm text-gray-500">Bahan: {{ $p->bahan->nama_bahan ?? 'Tidak ada bahan' }}</p>
            </div>
            @endforeach
        </div>
    </section>

    <footer id="kontak" class="px-10 py-16 bg-black text-white border-t-4 border-aira-red">
        <div class="grid md:grid-cols-2 gap-10 items-center max-w-6xl mx-auto">
            <div>
                <h2 class="text-3xl font-black mb-4 uppercase tracking-widest">CV AIRA Advertising</h2>
                <p class="text-gray-400 italic">"Kualitas dan kecepatan adalah prioritas kami."</p>
            </div>
            <div class="text-right space-y-3 font-bold tracking-wider text-sm">
                <p class="flex items-center justify-end gap-3 hover:text-aira-red transition">
                    <a href="mailto:aira.adv55@gmail.com">aira.adv55@gmail.com</a>
                    <i class="fa-solid fa-envelope text-aira-red"></i>
                </p>
                <p class="flex items-center justify-end gap-3 hover:text-aira-red transition">
                    <a href="https://wa.me/6281320053678">0813-2005-3678</a>
                    <i class="fa-brands fa-whatsapp text-aira-red"></i>
                </p>
                <p class="flex items-center justify-end gap-3 hover:text-aira-red transition">
                    <a href="https://instagram.com/airaadv">@airaadv</a>
                    <i class="fa-brands fa-instagram text-aira-red"></i>
                </p>
            </div>
        </div>
        <div class="mt-12 pt-8 border-t border-gray-800 text-center text-sm text-gray-500 uppercase tracking-widest">
            &copy; 2026 CV AIRA Advertising. All rights reserved.
        </div>
    </footer>

</body>
</html>