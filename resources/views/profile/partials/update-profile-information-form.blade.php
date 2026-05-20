<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Informasi Profil</h2>
        <p class="mt-1 text-sm text-gray-600">Perbarui informasi profil akun Anda.</p>
    </header>

    @if(Auth::guard('petugas')->check())
        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
            @csrf @method('patch')
            <div><x-input-label for="nama_lengkap" :value="__('Nama Lengkap')" /><x-text-input id="nama_lengkap" name="nama_lengkap" type="text" class="mt-1 block w-full" :value="old('nama_lengkap', $user->nama_lengkap)" required autofocus /><x-input-error class="mt-2" :messages="$errors->get('nama_lengkap')" /></div>
            <div><x-input-label for="email" :value="__('Email')" /><x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required /><x-input-error class="mt-2" :messages="$errors->get('email')" /></div>
            <div class="flex items-center gap-4"><x-primary-button>{{ __('Simpan') }}</x-primary-button>@if (session('status') === 'profile-updated')<p class="text-sm text-gray-600">Tersimpan.</p>@endif</div>
        </form>
    @elseif(Auth::guard('pelanggan')->check())
        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
            @csrf @method('patch')
            <div><x-input-label for="username" :value="__('Username')" /><x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $user->username)" required /><x-input-error class="mt-2" :messages="$errors->get('username')" /></div>
            <div><x-input-label for="alamat" :value="__('Alamat')" /><x-text-input id="alamat" name="alamat" type="text" class="mt-1 block w-full" :value="old('alamat', $user->alamat)" /><x-input-error class="mt-2" :messages="$errors->get('alamat')" /></div>
            <div><x-input-label for="no_telpon" :value="__('No. Telepon')" /><x-text-input id="no_telpon" name="no_telpon" type="text" class="mt-1 block w-full" :value="old('no_telpon', $user->no_telpon)" /><x-input-error class="mt-2" :messages="$errors->get('no_telpon')" /></div>
            <div class="flex items-center gap-4"><x-primary-button>{{ __('Simpan') }}</x-primary-button>@if (session('status') === 'profile-updated')<p class="text-sm text-gray-600">Tersimpan.</p>@endif</div>
        </form>
    @endif
</section>