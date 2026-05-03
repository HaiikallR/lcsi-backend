<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermintaanUpgradeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_pelanggan' => $this->id_pelanggan,
            'paket_lama' => $this->paket_lama,
            'paket_baru' => $this->paket_baru,
            'harga_baru' => $this->harga_baru,
            'status' => $this->status,
            'disetujui_pada' => $this->disetujui_pada,
            'ditolak_pada' => $this->ditolak_pada,
            'pelanggan' => $this->whenLoaded('pelanggan', fn () => [
                'id' => $this->pelanggan->id,
                'nama' => $this->pelanggan->nama,
                'email' => $this->pelanggan->email,
                'paket_langganan' => $this->pelanggan->paket_langganan,
            ]),
            'dibuat_pada' => $this->created_at?->toDateTimeString(),
            'diubah_pada' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
