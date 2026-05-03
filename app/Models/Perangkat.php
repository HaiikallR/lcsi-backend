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
        'terpasang_di',
        'status',
        'id_admin',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'id_admin');
    }
}
