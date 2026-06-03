<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $table = 'm_satuan';
    protected $primaryKey = 'id_satuan';
    protected $fillable = ['nama_satuan' ];

    public function produks()
    {
        return $this->hasMany(Produk::class, 'id_satuan', 'id_satuan');
    }
}
