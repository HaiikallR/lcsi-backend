<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PemasukanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_pelanggan' => $this->id_pelanggan,
            'jenis_pemasukan' => $this->jenis_pemasukan,
            'jumlah_bayar' => $this->jumlah_bayar,
            'metode_bayar' => $this->metode_bayar,
            'bukti_bayar' => $this->bukti_bayar,
            'keterangan' => $this->keterangan,
            'status' => $this->status,
            'bulan_tagihan' => $this->bulan_tagihan,
            'tahun_tagihan' => $this->tahun_tagihan,
            'tanggal_bayar' => $this->tanggal_bayar,
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
