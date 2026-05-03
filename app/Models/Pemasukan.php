<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemasukan extends Model
{

    protected $fillable = [
        'id_pelanggan',
        'jenis_pemasukan',
        'jumlah_bayar',
        'metode_bayar',
        'bukti_bayar',
        'keterangan',
        'status',
        'bulan_tagihan',
        'tahun_tagihan',
        'tanggal_bayar'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id');
    }
}
