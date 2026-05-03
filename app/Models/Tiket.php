<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    protected $fillable = [
        'id_pelanggan',
        'nama_pelanggan',
        'alamat_pelanggan',
        'nomor_hp_pelanggan',
        'id_teknisi',
        'jenis_pekerjaan',
        'ongkos_teknisi',
        'status',
        'tanggal_selesai'
    ];

    // Relasi ke Pelanggan (Many to One)
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id');
    }

    // Relasi ke Pengeluaran (One to Many)
    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class, 'id_tiket', 'id');
    }
}
