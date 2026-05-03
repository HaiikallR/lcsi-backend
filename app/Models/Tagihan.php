<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $primaryKey = 'id_tagihan';

    protected $fillable = [
        'id_pelanggan',
        'jumlah',
        'bulan',
        'tahun',
        'bukti_bayar',
        'catatan admin',
        'status',
        'tanggal_bayar',
        'tanggal_verifikasi',


    ];

    // Relasi: Tagihan ini milik siapa?
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_user', 'id_pelanggan');
    }
}
