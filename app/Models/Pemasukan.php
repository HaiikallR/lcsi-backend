<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PemasukanFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pemasukan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_pelanggan',
        'id_tagihan',
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

    protected function casts(): array
    {
        return [
            'tanggal_bayar' => 'datetime',
        ];
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id');
    }
    public function tagihan(): BelongsTo
    {
        return $this->belongsTo(Tagihan::class, 'id_tagihan', 'id');
    }

    protected static function newFactory(): PemasukanFactory
    {
        return PemasukanFactory::new();
    }
}
