<?php

/**
 * app/View/Components/Layout/Sidebar.php
 * ─────────────────────────────────────────────────────────────────────────
 * BLADE COMPONENT CLASS
 *
 * Ini adalah "class" dari Blade Component <x-layout.sidebar>.
 * Laravel akan otomatis cari view-nya di:
 * resources/views/components/layout/sidebar.blade.php
 *
 * Keuntungan pakai Component daripada @include biasa:
 * • Bisa punya logika PHP sendiri (method, computed property)
 * • Lebih reusable dan testable
 * • Props bisa di-type-hint
 */

namespace App\View\Components\Layout;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Sidebar extends Component
{
    /**
     * Data menu navigasi sidebar.
     * Dengan mendefinisikan menu di sini (bukan di view),
     * kita bisa tambahkan logika seperti permission check per menu.
     */
    public array $menus;

    public function __construct()
    {
        $this->menus = [
            [
                'label' => 'Utama',
                'items' => [
                    ['route' => 'dashboard',  'label' => 'Dashboard',  'icon' => 'home'],
                ],
            ],
            [
                'label' => 'Pengguna',
                'items' => [
                    ['route' => 'admin.index',     'label' => 'Admin',     'icon' => 'shield-user', 'pattern' => 'admin.*'],
                    ['route' => 'pelanggan.index',  'label' => 'Pelanggan', 'icon' => 'users',       'pattern' => 'pelanggan.*'],
                    ['route' => 'teknisi.index',    'label' => 'Teknisi',   'icon' => 'wrench',      'pattern' => 'teknisi.*'],
                ],
            ],
            [
                'label' => 'Operasional',
                'items' => [
                    ['route' => 'tiket.index',              'label' => 'Tiket',             'icon' => 'ticket',   'pattern' => 'tiket.*'],
                    ['route' => 'perangkat.index',          'label' => 'Perangkat',         'icon' => 'router',   'pattern' => 'perangkat.*'],
                    ['route' => 'permintaan-upgrade.index', 'label' => 'Permintaan Upgrade', 'icon' => 'arrow-up-circle', 'pattern' => 'permintaan-upgrade.*'],
                ],
            ],
            [
                'label' => 'Keuangan',
                'items' => [
                    ['route' => 'tagihan.index',    'label' => 'Tagihan',    'icon' => 'file-text', 'pattern' => 'tagihan.*'],
                    ['route' => 'pemasukan.index',  'label' => 'Pemasukan',  'icon' => 'trending-up', 'pattern' => 'pemasukan.*'],
                    ['route' => 'pengeluaran.index', 'label' => 'Pengeluaran', 'icon' => 'trending-down', 'pattern' => 'pengeluaran.*'],
                    ['route' => 'verifikasi-pembayaran.index', 'label' => 'Verifikasi Pembayaran', 'icon' => 'check-circle', 'pattern' => 'verifikasi-pembayaran.*'],

                ],
            ],
            [
                'label' => 'Lainnya',
                'items' => [
                    ['route' => 'notifikasi.index', 'label' => 'Notifikasi', 'icon' => 'bell',          'pattern' => 'notifikasi.*'],
                    ['route' => 'pertanyaan.index', 'label' => 'FAQ',        'icon' => 'help-circle',   'pattern' => 'pertanyaan.*'],
                ],
            ],
        ];
    }

    public function render(): View|Closure|string
    {
        return view('Components.Layout.Sidebar');
    }
}
