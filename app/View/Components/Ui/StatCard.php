<?php

/**
 * app/View/Components/Ui/StatCard.php
 * ─────────────────────────────────────────────────────────────────────────
 * BLADE COMPONENT dengan multiple PROPS
 *
 * Dipakai di halaman dashboard untuk kartu statistik.
 * Contoh pemakaian di blade:
 *
 *   <x-ui.stat-card
 *       label="Total Pelanggan"
 *       :value="$statistik['total_pelanggan']"
 *       icon="users"
 *       color="blue"
 *   />
 */

namespace App\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatCard extends Component
{
    public string $colorClasses;

    public function __construct(
        public string $label,
        public string|int $value,
        public string $icon = 'bar-chart',
        public string $color = 'blue',      // blue | green | yellow | red | purple | orange
        public ?string $prefix = null,       // contoh: 'Rp'
        public ?string $suffix = null,       // contoh: '%'
        public ?string $sub = null,          // teks kecil di bawah value
    ) {
        $this->colorClasses = match ($color) {
            'green' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
            'yellow' => 'bg-amber-50 text-amber-600 border-amber-100',
            'red' => 'bg-red-50 text-red-600 border-red-100',
            'purple' => 'bg-purple-50 text-purple-600 border-purple-100',
            'orange' => 'bg-orange-50 text-orange-600 border-orange-100',
            default => 'bg-blue-50 text-blue-600 border-blue-100',
        };
    }

    public function render(): View|Closure|string
    {
        return view('Components.ui.stat-card');
    }
}
