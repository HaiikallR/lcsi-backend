<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TiketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'jenis_pekerjaan' => $this->jenis_pekerjaan,
            'ongkos_teknisi' => $this->ongkos_teknisi,
            'status' => $this->status,
            'tanggal_selesai' => $this->tanggal_selesai?->toDateTimeString(),
            'calon_pelanggan_nama' => $this->calon_pelanggan_nama,
            'calon_pelanggan_no_hp' => $this->calon_pelanggan_no_hp,
            'calon_pelanggan_alamat' => $this->calon_pelanggan_alamat,
            'id_pelanggan' => $this->id_pelanggan,
            'id_teknisi' => $this->id_teknisi,
            'pelanggan' => $this->whenLoaded('pelanggan', fn () => [
                'id' => $this->pelanggan->id,
                'nama' => $this->pelanggan->nama,
                'email' => $this->pelanggan->email,
            ]),
            'teknisi' => $this->whenLoaded('teknisi', fn () => [
                'id' => $this->teknisi->id,
                'nama' => $this->teknisi->nama,
                'no_hp' => $this->teknisi->no_hp,
                'status' => $this->teknisi->status,
            ]),
            'pengeluaran' => $this->whenLoaded('pengeluaran', fn () => [
                'id' => $this->pengeluaran->id,
                'judul' => $this->pengeluaran->judul,
                'jumlah' => $this->pengeluaran->jumlah,
            ]),
            'dibuat_pada' => $this->created_at?->toDateTimeString(),
            'diubah_pada' => $this->updated_at?->toDateTimeString(),
        ];
    }
}