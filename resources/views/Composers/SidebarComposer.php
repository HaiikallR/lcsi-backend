<?php

/**
 * app/Http/View/Composers/SidebarComposer.php
 * ─────────────────────────────────────────────────────────────────────────
 * VIEW COMPOSER
 *
 * View Composer adalah cara Laravel untuk "inject" data ke view tertentu
 * secara otomatis — tanpa harus kirim data itu dari setiap controller.
 *
 * Contoh kasus: badge angka notifikasi di sidebar.
 * Daripada setiap controller kirim $totalNotifikasi,
 * cukup daftarkan di Composer sekali, semua view dapat otomatis.
 *
 * Cara mendaftarkan: di AppServiceProvider.php tambahkan:
 *
 *   use App\Http\View\Composers\SidebarComposer;
 *
 *   public function boot(): void
 *   {
 *       View::composer('components.layout.sidebar', SidebarComposer::class);
 *   }
 */

namespace App\Http\View\Composers;

use App\Models\PermintaanUpgrade;
use App\Models\Tagihan;
use App\Models\Tiket;
use Illuminate\View\View;

class SidebarComposer
{
    /**
     * compose() dipanggil otomatis setiap kali view sidebar di-render.
     * Data yang di-share di sini tersedia sebagai variabel di sidebar.blade.php
     */
    public function compose(View $view): void
    {
        $view->with([
            // Jumlah tiket yang masih aktif (untuk badge di sidebar)
            'tiketAktif' => Tiket::where('status', 'in progress')->count(),

            // Jumlah tagihan yang belum dibayar
            'tagihanBelumBayar' => Tagihan::where('status', 'belum bayar')->count(),

            // Jumlah permintaan upgrade yang menunggu persetujuan
            'upgradeMenunggu' => PermintaanUpgrade::where('status', 'menunggu')->count(),
        ]);
    }
}
