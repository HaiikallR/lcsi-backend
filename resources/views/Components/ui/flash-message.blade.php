{{--
    components/ui/flash-message.blade.php
    ─────────────────────────────────────────────────────────────────────────
    Variabel $type dan $message tersedia dari FlashMessage.php

    Alpine.js dipakai untuk auto-dismiss setelah 4 detik
    dan animasi fade-out.
--}}
@if($message)
    @php
        $styles = match($type) {
            'success' => ['bg' => 'bg-emerald-50 border-emerald-200', 'text' => 'text-emerald-700', 'icon' => 'check-circle', 'iconColor' => 'text-emerald-500'],
            'error'   => ['bg' => 'bg-red-50 border-red-200',         'text' => 'text-red-700',     'icon' => 'x-circle',     'iconColor' => 'text-red-500'],
            'warning' => ['bg' => 'bg-amber-50 border-amber-200',     'text' => 'text-amber-700',   'icon' => 'alert-triangle','iconColor' => 'text-amber-500'],
            default   => ['bg' => 'bg-blue-50 border-blue-200',       'text' => 'text-blue-700',    'icon' => 'info',          'iconColor' => 'text-blue-500'],
        };
    @endphp

    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 4000)"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="mb-5 flex items-center gap-3 px-4 py-3.5 rounded-xl border {{ $styles['bg'] }} {{ $styles['text'] }}"
    >
        <span class="{{ $styles['iconColor'] }} flex-shrink-0">
            @include('Components.ui.icons', ['icon' => $styles['icon'], 'class' => 'w-5 h-5'])
        </span>
        <p class="text-sm font-medium flex-1">{{ $message }}</p>
        <button @click="show = false" class="opacity-60 hover:opacity-100 transition">
            @include('Components.ui.icons', ['icon' => 'x', 'class' => 'w-4 h-4'])
        </button>
    </div>
@endif