<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_pelanggan',
        'id_teknisi',
        'jenis_pekerjaan',
        'calon_pelanggan_nama',
        'calon_pelanggan_no_hp',
        'calon_pelanggan_alamat',
        'ongkos_teknisi',
        'status',
        'tanggal_selesai',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_selesai' => 'datetime',
        ];
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    public function teknisi(): BelongsTo
    {
        return $this->belongsTo(Teknisi::class, 'id_teknisi');
    }

    public function pengeluaran(): HasOne
    {
        return $this->hasOne(Pengeluaran::class, 'id_tiket');
    }
}
