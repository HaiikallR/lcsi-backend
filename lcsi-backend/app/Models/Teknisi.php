<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teknisi extends Model
{
    protected $table = 'teknisi';
    protected $primaryKey = 'id_teknisi';

    protected $fillable = [
        'nama_teknisi',
        'no_hp',
        'wilayah_tugas',
        'status'
    ];

    // Relasi: Satu teknisi bisa mengerjakan banyak tiket
    public function tiket()
    {
        return $this->hasMany(Tiket::class, 'id_teknisi', 'id_teknisi');
    }
}
