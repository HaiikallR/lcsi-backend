<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanUpgrade extends Model
{
    protected $table = 'permintaan_upgrade';
    protected $primaryKey = 'id_permintaan_upgrade';

    protected $fillable = [
        'id_pelanggan',
        'paket_lama',
        'paket_baru',
        'harga_baru',
        'status',
        'waktu_pengajuan',
        'disetujui_pada',
        'ditolak_pada'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }
}
