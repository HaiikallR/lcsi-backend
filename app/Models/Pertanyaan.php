<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PertanyaanFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pertanyaan',
        'jawaban',
        'kategori',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    protected static function newFactory(): PertanyaanFactory
    {
        return PertanyaanFactory::new();
    }
}
