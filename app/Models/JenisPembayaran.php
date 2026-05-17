<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPembayaran extends Model
{
    protected $table = 'jenis_pembayaran';
    protected $primaryKey = 'id_jenis_pembayaran';
    protected $fillable = ['nama_metode', 'no_rekening', 'atas_nama'];
}