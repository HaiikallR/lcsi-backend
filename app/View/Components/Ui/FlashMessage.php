<?php

/**
 * app/View/Components/Ui/FlashMessage.php
 * ─────────────────────────────────────────────────────────────────────────
 * BLADE COMPONENT — Flash Message
 *
 * Komponen ini otomatis cek session('success'), session('error'),
 * session('warning'), session('info') dan tampilkan alert yang sesuai.
 *
 * Cukup taruh <x-ui.flash-message /> di layout sekali,
 * tidak perlu tulis ulang di setiap halaman.
 */

namespace App\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FlashMessage extends Component
{
    public ?string $type;

    public ?string $message;

    public function __construct()
    {
        // Prioritas: success → error → warning → info
        if (session('success')) {
            $this->type = 'success';
            $this->message = session('success');
        } elseif (session('error')) {
            $this->type = 'error';
            $this->message = session('error');
        } elseif (session('warning')) {
            $this->type = 'warning';
            $this->message = session('warning');
        } elseif (session('info')) {
            $this->type = 'info';
            $this->message = session('info');
        } else {
            $this->type = null;
            $this->message = null;
        }
    }

    public function render(): View|Closure|string
    {
        return view('Components.ui.flash-message');
    }
}
