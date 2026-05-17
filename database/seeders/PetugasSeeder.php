<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Petugas;
use Illuminate\Support\Facades\Hash;

class PetugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['username'=>'owner', 'email'=>'owner@aira.com', 'password'=>Hash::make('password'), 'nama_lengkap'=>'Pemilik Aira', 'level'=>'Owner'],
            ['username'=>'admin', 'email'=>'admin@aira.com', 'password'=>Hash::make('password'), 'nama_lengkap'=>'Administrasi', 'level'=>'Administrasi'],
            ['username'=>'desain', 'email'=>'desain@aira.com', 'password'=>Hash::make('password'), 'nama_lengkap'=>'Desain', 'level'=>'Desain'],
            ['username'=>'produksi', 'email'=>'produksi@aira.com', 'password'=>Hash::make('password'), 'nama_lengkap'=>'Produksi', 'level'=>'Produksi'],
        ];
        foreach($data as $d) Petugas::create($d);
    }
}
