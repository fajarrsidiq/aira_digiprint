<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Hash;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pelanggan::create([
            'username' => 'pelanggan1',
            'password' => Hash::make('password'),
            'alamat' => 'Jl. Contoh No. 1',
            'no_telpon' => '08123456789',
        ]);
    }
}
