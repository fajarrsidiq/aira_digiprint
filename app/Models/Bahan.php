<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bahan extends Model
{
    protected $table = 'm_bahan';
    protected $primaryKey = 'id_bahan';
    protected $fillable = ['nama_bahan'];
}