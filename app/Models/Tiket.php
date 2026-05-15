<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pengeluaran;

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
    protected static function booted()
    {
        static::updated(function ($tiket) {
            // Cek apakah status berubah menjadi 'Selesai'
            // dan pastikan tidak membuat pengeluaran ganda (cek jika sudah ada pengeluaran untuk tiket ini)
            if ($tiket->isDirty('status') && $tiket->status === 'selesai') {

                // Logika otomatis membuat pengeluaran
                \App\Models\Pengeluaran::create([
                    'id_tiket' => $tiket->id,
                    'id_teknisi' => $tiket->id_teknisi,
                    'judul'    => 'Biaya Teknisi: ' . $tiket->jenis_pekerjaan,
                    'jumlah'   => $tiket->ongkos_teknisi ?? 0,
                    'kategori' => 'Jasa Teknisi',
                    'bulan'    => now()->translatedFormat('F'), // Contoh: Mei
                    'tahun'    => now()->format('Y'),           // Contoh: 2026
                ]);
            }
        });
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
