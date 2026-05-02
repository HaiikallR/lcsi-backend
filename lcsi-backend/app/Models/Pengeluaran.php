<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{

    protected $fillable = [
        'id_tiket',
        'judul',
        'jumlah',
        'kategori',
        'bulan',
        'tahun',
        'teknisi'
    ];

    public function tiket()
    {
        return $this->belongsTo(Tiket::class, 'id_tiket', 'id');
    }
}
