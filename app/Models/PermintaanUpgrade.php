<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanUpgrade extends Model
{

    protected $fillable = [
        'id_pelanggan',
        'paket_lama',
        'paket_baru',
        'harga_baru',
        'status',
        'disetujui_pada',
        'ditolak_pada'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id');
    }
}
