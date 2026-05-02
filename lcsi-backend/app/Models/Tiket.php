<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    protected $table = 'tiket';
    protected $primaryKey = 'id_tiket';

    protected $fillable = [
        'id_pelanggan',
        'nama_pelanggan',
        'alamat_pelanggan',
        'phone_pelanggan',
        'id_teknisi',
        'nama_teknisi',
        'jenis_pekerjaan',
        'ongkos_teknisi',
        'status',
        'tanggal'
    ];

    // Relasi ke Pelanggan (Many to One)
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    // Relasi ke Pengeluaran (One to Many)
    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class, 'id_tiket', 'id_tiket');
    }
}
