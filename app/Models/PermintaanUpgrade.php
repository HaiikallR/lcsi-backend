<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PermintaanUpgradeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermintaanUpgrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_pelanggan',
        'paket_lama',
        'paket_baru',
        'harga_baru',
        'status',
        'disetujui_pada',
        'ditolak_pada'
    ];

    protected function casts(): array
    {
        return [
            'disetujui_pada' => 'datetime',
            'ditolak_pada' => 'datetime',
        ];
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id');
    }

    protected static function newFactory(): PermintaanUpgradeFactory
    {
        return PermintaanUpgradeFactory::new();
    }
}
