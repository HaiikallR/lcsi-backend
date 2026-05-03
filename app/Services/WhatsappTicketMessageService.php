<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tiket;

class WhatsappTicketMessageService
{
    public function buildPayload(Tiket $tiket): array
    {
        $tujuan = preg_replace('/\D+/', '', (string) $tiket->teknisi?->no_hp);

        $detailPelanggan = $tiket->jenis_pekerjaan === 'pasang baru'
            ? 'Calon pelanggan: '.$tiket->calon_pelanggan_nama.' / '.$tiket->calon_pelanggan_no_hp.' / '.$tiket->calon_pelanggan_alamat
            : 'Pelanggan: '.($tiket->pelanggan?->nama ?? '-');

        $pesan = "Tiket Pekerjaan\n"
            ."Jenis: {$tiket->jenis_pekerjaan}\n"
            ."{$detailPelanggan}\n"
            ."Teknisi: ".($tiket->teknisi?->nama ?? '-') ."\n"
            ."Ongkos: Rp {$tiket->ongkos_teknisi}\n"
            ."Status: {$tiket->status}";

        return [
            'pesan' => $pesan,
            'url_whatsapp' => $tujuan !== ''
                ? 'https://wa.me/'.$tujuan.'?text='.rawurlencode($pesan)
                : '',
        ];
    }
}