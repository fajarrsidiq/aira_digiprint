<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Petugas extends Authenticatable
{
    protected $table = 'petugas';
    protected $primaryKey = 'id_petugas';
    protected $fillable = ['username', 'email', 'password', 'nama_lengkap', 'level'];
    protected $hidden = ['password', 'remember_token'];
}