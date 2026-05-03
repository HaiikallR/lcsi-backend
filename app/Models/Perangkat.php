<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Perangkat extends Model
{
    /** @use HasFactory<\Database\Factories\PerangkatFactory> */
    use HasFactory;

    protected $fillable = [
        'nama_perangkat',
        'merk',
        'serial_number',
        'status',
        'id_pelanggan',
    ];

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }
}
