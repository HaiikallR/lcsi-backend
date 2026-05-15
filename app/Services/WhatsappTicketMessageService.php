<?php

namespace App\Services;

use App\Models\Tiket;

class WhatsappTicketMessageService
{
    /**
     * Build pesan WhatsApp untuk dikirim ke teknisi
     */
    public function buildPesan(Tiket $tiket): string
    {
        // Tentukan nama & info pelanggan
        // (bisa pelanggan terdaftar atau calon pelanggan)
        $namaPelanggan  = $tiket->pelanggan?->nama
            ?? $tiket->calon_pelanggan_nama
            ?? 'Tidak diketahui';

        $nomorPelanggan = $tiket->pelanggan?->no_hp
            ?? $tiket->calon_pelanggan_no_hp
            ?? 'Tidak ada nomor';

        $alamat         = $tiket->pelanggan?->alamat
            ?? $tiket->calon_pelanggan_alamat
            ?? 'Tidak ada alamat';

        $paket          = $tiket->pelanggan?->paket_langganan
            ?? '-';

        // Format pesan WhatsApp
        $pesan = "🔧 *DETAIL PEKERJAAN LCSI*\n";
        $pesan .= "━━━━━━━━━━━━━━━━━━━━\n\n";

        $pesan .= "📋 *Jenis Pekerjaan:*\n";
        $pesan .= "{$tiket->jenis_pekerjaan}\n\n";

        $pesan .= "👤 *Data Pelanggan:*\n";
        $pesan .= "Nama    : {$namaPelanggan}\n";
        $pesan .= "No. HP  : {$nomorPelanggan}\n";
        $pesan .= "Alamat  : {$alamat}\n";

        if ($tiket->pelanggan) {
            $pesan .= "Paket   : {$paket}\n";
        }

        $pesan .= "\n👷 *Teknisi yang Ditugaskan:*\n";
        $pesan .= "Nama    : {$tiket->teknisi?->nama}\n";

        $pesan .= "\n📌 *Status:* {$tiket->status}\n";
        $pesan .= "🗓️ *Tanggal:* " . now()->format('d M Y H:i') . "\n\n";

        $pesan .= "━━━━━━━━━━━━━━━━━━━━\n";
        $pesan .= "_Pesan ini dikirim otomatis oleh sistem LCSI_";

        return $pesan;
    }

    /**
     * Build URL WhatsApp dengan nomor & pesan
     */
    public function buildUrl(string $nomorHp, string $pesan): string
    {
        // Bersihkan nomor HP
        // Contoh: 08123456789 → 628123456789
        $nomor = $this->formatNomor($nomorHp);

        // Encode pesan untuk URL
        $pesanEncoded = urlencode($pesan);

        return "https://wa.me/{$nomor}?text={$pesanEncoded}";
    }

    /**
     * Format nomor HP ke format internasional
     */
    private function formatNomor(string $nomor): string
    {
        // Hapus semua karakter selain angka
        $nomor = preg_replace('/[^0-9]/', '', $nomor);

        // 08xx → 628xx
        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        }

        // +628xx → 628xx
        if (str_starts_with($nomor, '+')) {
            $nomor = substr($nomor, 1);
        }

        return $nomor;
    }
}
