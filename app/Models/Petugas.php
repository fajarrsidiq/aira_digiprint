<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Petugas extends Model
{
    use Notifiable;
    protected $table = 'petugas';
    protected $primaryKey = 'id_petugas';
    protected $fillable = ['username', 'email', 'password', 'nama_lengkap', 'level'];
    protected $hidden = ['password', 'remember_token'];
}
