<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    protected $table = 'pengeluaran';
    protected $primaryKey = 'id_pengeluaran';

    protected $fillable = [
        'id_tiket',
        'judul',
        'jumlah',
        'kategori',
        'bulan',
        'tahun',
        'tanggal',
        'teknisi'
    ];

    // Relasi: Pengeluaran ini milik tiket mana?
    public function tiket()
    {
        return $this->belongsTo(Tiket::class, 'id_tiket', 'id_tiket');
    }
}
