<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_tiket',
        'id_teknisi',
        'judul',
        'jumlah',
        'kategori',
        'bulan',
        'tahun',
    ];

    public function tiket(): BelongsTo
    {
        return $this->belongsTo(Tiket::class, 'id_tiket', 'id');
    }

    public function teknisi(): BelongsTo
    {
        return $this->belongsTo(Teknisi::class, 'id_teknisi', 'id');
    }
}
