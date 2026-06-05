<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\Petugas;
use App\Models\Pelanggan;

class ProfileController extends Controller
{
    public function edit()
    {
        if (Auth::guard('petugas')->check()) {
            $user = Auth::guard('petugas')->user();
        } elseif (Auth::guard('pelanggan')->check()) {
            $user = Auth::guard('pelanggan')->user();
        } else {
            return redirect()->route('login');
        }
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        if (Auth::guard('petugas')->check()) {

            /** @var Petugas $user */
            $user = Auth::guard('petugas')->user();

            $validated = $request->validate([
                'nama_lengkap' => 'required|string|max:100',
                'email' => 'required|email|unique:petugas,email,' . $user->id_petugas . ',id_petugas',
            ]);

            $user->update($validated);

        } elseif (Auth::guard('pelanggan')->check()) {

            /** @var Pelanggan $user */
            $user = Auth::guard('pelanggan')->user();

            $validated = $request->validate([
                'nama_pelanggan' => 'required|string|max:255',
                'alamat' => 'nullable|string',
                'no_telpon' => 'nullable|string|max:15',
            ]);

            $user->update($validated);

        } else {
            return redirect()->route('login');
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request)
    {
        return back()->with('error', 'Fitur hapus akun dinonaktifkan.');
    }
}