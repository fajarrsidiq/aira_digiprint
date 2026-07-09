<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPembayaran extends Model
{
    protected $table = 'jenis_pembayaran';
    protected $primaryKey = 'id_jenis_pembayaran';
    protected $fillable = ['nama_metode', 'no_rekening', 'atas_nama'];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_pembayaran', 'id_jenis_pembayaran');
    }
    public function setNamaMetodeAttribute($value)
    {
        $this->attributes['nama_metode'] = strtolower($value);
    }
}