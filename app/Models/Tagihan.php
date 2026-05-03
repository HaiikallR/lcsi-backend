<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TagihanFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tagihan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_pelanggan',
        'jumlah',
        'bulan',
        'tahun',
        'catatan',
        'status',
        'tanggal_bayar',
        'tanggal_verifikasi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bayar' => 'datetime',
            'tanggal_verifikasi' => 'datetime',
        ];
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id');
    }

    protected static function newFactory(): TagihanFactory
    {
        return TagihanFactory::new();
    }
}
