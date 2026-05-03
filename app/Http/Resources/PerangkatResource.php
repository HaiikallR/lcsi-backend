<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PerangkatResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'nama_perangkat' => $this->nama_perangkat,
      'merk' => $this->merk,
      'serial_number' => $this->serial_number,
      'status' => $this->status,
      'id_pelanggan' => $this->id_pelanggan,
      'pelanggan' => $this->whenLoaded('pelanggan', fn() => [
        'id' => $this->pelanggan->id,
        'nama' => $this->pelanggan->nama,
        'email' => $this->pelanggan->email,
      ]),
      'dibuat_pada' => $this->created_at?->toDateTimeString(),
      'diubah_pada' => $this->updated_at?->toDateTimeString(),
    ];
  }
}
