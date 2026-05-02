<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggans';
    protected $primaryKey = 'id_pelanggan';

    protected $fillable = [
        'nama',
        'email',
        'kata_sandi',
        'no_telp',
        'alamat',
        'paket_langganan',
        'status',
        'total_tagihan',
        'fcm_token',
    ];

    // Relasi: Satu pelanggan bisa punya banyak tiket
    public function tiket()
    {
        return $this->hasMany(Tiket::class, 'id_pelanggan', 'id_pelanggan');
    }
}
