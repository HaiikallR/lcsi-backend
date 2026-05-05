<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotifikasiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_pelanggan' => $this->id_pelanggan,
            'pelanggan' => [
                'id' => $this->pelanggan?->id,
                'nama' => $this->pelanggan?->nama,
                'email' => $this->pelanggan?->email,
            ],
            'judul' => $this->judul,
            'pesan' => $this->pesan,
            'kategori' => $this->kategori,
            'dibuat_pada' => $this->created_at?->toDateTimeString(),
            'diubah_pada' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
