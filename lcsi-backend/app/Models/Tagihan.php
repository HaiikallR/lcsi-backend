<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $table = 'tagihan';
    protected $primaryKey = 'id_tagihan';

    protected $fillable = [
        'id_user',
        'bulan',
        'tahun',
        'jumlah_tagihan',
        'status',
        'jatuh_tempo'
    ];

    // Relasi: Tagihan ini milik siapa?
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_user', 'id_pelanggan');
    }
}
