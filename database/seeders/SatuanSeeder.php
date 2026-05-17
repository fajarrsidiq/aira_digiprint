<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Satuan;

class SatuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $list = ['pcs', 'lembar', 'meter'];
        foreach($list as $nama) Satuan::create(['nama_satuan' => $nama]);
    }
}
