<?php

/**
 * app/View/Components/Layout/Navbar.php
 * ─────────────────────────────────────────────────────────────────────────
 * BLADE COMPONENT dengan PROPS
 *
 * Komponen ini menerima prop :title dari layout.
 * Di view dipanggil: <x-layout.navbar :title="..." />
 */

namespace App\View\Components\Layout;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Navbar extends Component
{
    /**
     * Constructor — semua parameter otomatis jadi prop yang bisa
     * di-pass dari template: <x-layout.navbar :title="$title" />
     */
    public function __construct(
        public string $title = 'Dashboard'
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.layout.navbar');
    }
}
