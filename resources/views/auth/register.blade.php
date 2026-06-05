<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="nama_pelanggan" :value="__('Nama Lengkap')" />
            <x-text-input id="nama_pelanggan" class="block mt-1 w-full" type="text" name="nama_pelanggan" :value="old('nama_pelanggan')" required autofocus oninput="generateUsername(this.value)" />
            <x-input-error :messages="$errors->get('nama_pelanggan')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="alamat" :value="__('Alamat')" />
            <x-text-input id="alamat" class="block mt-1 w-full" type="text" name="alamat" :value="old('alamat')" required />
            <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="no_telpon" :value="__('No. Telepon')" />
            <x-text-input id="no_telpon" class="block mt-1 w-full" type="text" name="no_telpon" :value="old('no_telpon')" required />
            <x-input-error :messages="$errors->get('no_telpon')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        function generateUsername(nama) {
            // Jika input nama kosong, kosongkan juga input username
            if (!nama) {
                document.getElementById('username').value = '';
                return;
            }

            // 1. Ubah tulisan menjadi huruf kecil semua
            // 2. Bersihkan karakter simbol/tanda baca, sisakan hanya huruf, angka, dan spasi
            // 3. Hapus semua spasi agar menyatu menjadi satu kata tunggal
            let usernameSaran = nama.toLowerCase()
                                    .replace(/[^a-z0-9 ]/g, '')
                                    .replace(/\s+/g, '');

            // 4. Buat 2 digit angka acak (dari angka 10 sampai 99) agar username lebih unik di database
            let angkaAcak = Math.floor(10 + Math.random() * 90); 

            // 5. Masukkan gabungan nama & angka acak tersebut langsung ke input username
            document.getElementById('username').value = usernameSaran + angkaAcak;
        }
    </script>
</x-guest-layout>