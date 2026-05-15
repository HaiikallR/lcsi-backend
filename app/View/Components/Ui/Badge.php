<?php

/**
 * app/View/Components/Ui/Badge.php
 * ─────────────────────────────────────────────────────────────────────────
 * BLADE COMPONENT — Status Badge
 *
 * Dipakai untuk menampilkan status dengan warna yang konsisten
 * di seluruh aplikasi.
 *
 * Contoh pemakaian:
 *   <x-ui.badge status="aktif" />
 *   <x-ui.badge status="in progress" />
 *   <x-ui.badge status="selesai" />
 */

namespace App\View\Components\ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class Badge extends Component
{
    public string $classes;

    public function __construct(
        public string $status,
    ) {
        $this->classes = match (strtolower($status)) {
            'aktif', 'lunas', 'selesai', 'disetujui', 'sudah bayar', 'tersedia' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
            'in progress', 'menunggu', 'digunakan' => 'bg-amber-100 text-amber-700 border-amber-200',
            'tidak aktif', 'ditolak', 'belum bayar' => 'bg-red-100 text-red-700 border-red-200',
            'perbaikan', 'siap' => 'bg-blue-100 text-blue-700 border-blue-200',
            default => 'bg-slate-100 text-slate-600 border-slate-200',
        };
    }

    public function render(): View|Closure|string
    {
        return view('Components.ui.Badge');
    }
}
