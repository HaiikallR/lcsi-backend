<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeknisiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'no_hp' => $this->no_hp,
            'status' => $this->status,
            'dibuat_pada' => $this->created_at?->toDateTimeString(),
            'diubah_pada' => $this->updated_at?->toDateTimeString(),
        ];
    }
}