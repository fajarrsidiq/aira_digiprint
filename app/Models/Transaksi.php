<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';  
    protected $fillable = [
        'no_invoice', 'id_pelanggan', 'id_petugas', 'id_pembayaran',
        'tanggal', 'total_tagihan', 'jumlah_bayar', 'bukti_bayar', 'status_pesanan'
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas', 'id_petugas');
    }

    public function pembayaran()
    {
        return $this->belongsTo(JenisPembayaran::class, 'id_pembayaran', 'id_jenis_pembayaran');
    }

    public function details()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }
}