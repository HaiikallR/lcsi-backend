<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $hidden = ['password'];

    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_hp',
        'alamat',
        'paket_langganan',
        'status',
        'fcm_token',
    ];

    public function tiket()
    {
        return $this->hasMany(Tiket::class, 'id_pelanggan', 'id');
    }
}
