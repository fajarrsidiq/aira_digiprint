<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Pelanggan extends Model
{
    use Notifiable;
    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';
    protected $fillable = ['username', 'password', 'alamat', 'no_telpon'];
    protected $hidden = ['password', 'remember_token'];
}
