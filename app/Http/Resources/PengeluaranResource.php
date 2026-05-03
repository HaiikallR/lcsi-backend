<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PengeluaranResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_tiket' => $this->id_tiket,
            'id_teknisi' => $this->id_teknisi,
            'judul' => $this->judul,
            'jumlah' => $this->jumlah,
            'kategori' => $this->kategori,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'tiket' => $this->whenLoaded('tiket', fn () => [
                'id' => $this->tiket->id,
                'jenis_pekerjaan' => $this->tiket->jenis_pekerjaan,
                'status' => $this->tiket->status,
            ]),
            'teknisi' => $this->whenLoaded('teknisi', fn () => [
                'id' => $this->teknisi->id,
                'nama' => $this->teknisi->nama,
            ]),
            'dibuat_pada' => $this->created_at?->toDateTimeString(),
            'diubah_pada' => $this->updated_at?->toDateTimeString(),
        ];
    }
}