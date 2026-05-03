<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Pengeluaran;
use App\Models\Tiket;
use Illuminate\Support\Facades\DB;

class TiketService
{
    /**
     * Normalisasi data tiket sebelum disimpan:
     * - pasang baru: kosongkan id_pelanggan
     * - selain pasang baru: kosongkan field calon pelanggan
     */
    public function normalizeData(array $data): array
    {
        if ($data['jenis_pekerjaan'] === 'pasang baru') {
            $data['id_pelanggan'] = null;
        } else {
            $data['calon_pelanggan_nama'] = null;
            $data['calon_pelanggan_no_hp'] = null;
            $data['calon_pelanggan_alamat'] = null;
        }

        return $data;
    }

    /**
     * Selesaikan tiket: update status ke 'selesai' dan buat pengeluaran ongkos teknisi.
     * Dibungkus DB transaction agar atomik — jika salah satu gagal, keduanya di-rollback.
     */
    public function selesai(Tiket $tiket): Pengeluaran
    {
        return DB::transaction(function () use ($tiket): Pengeluaran {
            if ($tiket->status !== 'selesai') {
                $tiket->update([
                    'status' => 'selesai',
                    'tanggal_selesai' => now(),
                ]);
            }

            /** @var Pengeluaran $pengeluaran */
            $pengeluaran = Pengeluaran::firstOrCreate(
                ['id_tiket' => $tiket->id],
                [
                    'id_teknisi' => $tiket->id_teknisi,
                    'judul' => 'Ongkos teknisi tiket #'.$tiket->id,
                    'jumlah' => $tiket->ongkos_teknisi,
                    'kategori' => 'ongkos teknisi',
                    'bulan' => now()->translatedFormat('F'),
                    'tahun' => (string) now()->year,
                ]
            );

            return $pengeluaran;
        });
    }
}
