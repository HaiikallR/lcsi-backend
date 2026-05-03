<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagihanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_pelanggan' => $this->id_pelanggan,
            'jumlah' => $this->jumlah,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'catatan' => $this->catatan,
            'status' => $this->status,
            'tanggal_bayar' => $this->tanggal_bayar,
            'tanggal_verifikasi' => $this->tanggal_verifikasi,
            'pelanggan' => $this->whenLoaded('pelanggan', fn () => [
                'id' => $this->pelanggan->id,
                'nama' => $this->pelanggan->nama,
                'email' => $this->pelanggan->email,
            ]),
            'dibuat_pada' => $this->created_at?->toDateTimeString(),
            'diubah_pada' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
