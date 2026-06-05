<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function update(Request $request)
    {
        // Cek guard aktif
        if (Auth::guard('petugas')->check()) {
            $user = Auth::guard('petugas')->user();
            $guard = 'petugas';
        } elseif (Auth::guard('pelanggan')->check()) {
            $user = Auth::guard('pelanggan')->user();
            $guard = 'pelanggan';
        } else {
            return redirect()->route('login');
        }

        /** @var \App\Models\Petugas|\App\Models\Pelanggan $user */

        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('Password saat ini salah.');
                }
            }],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Mengubah nilai property password secara langsung
        $user->update(['password' => Hash::make($request->password)]);

        // Login ulang dengan guard yang sama
        Auth::guard($guard)->login($user);

        // Redirect ke halaman profil
        return redirect()->route('profile.edit')->with('status', 'password-updated');
    }
}