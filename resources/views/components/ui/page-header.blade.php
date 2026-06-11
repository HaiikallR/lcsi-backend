{{--
    components/ui/page-header.blade.php
    ─────────────────────────────────────────────────────────────────────────
    ANONYMOUS BLADE COMPONENT (tanpa class PHP)
    Untuk komponen sederhana yang tidak butuh logika,
    kita bisa buat langsung sebagai file blade tanpa class.

    Props dideklarasikan dengan @props directive.

    Contoh pemakaian:
    <x-ui.page-header
        title="Manajemen Pelanggan"
        sub="Total {{ $total }} pelanggan terdaftar"
        create-route="pelanggan.create"
        create-label="Tambah Pelanggan"
    />
--}}
@props([
    'title',
    'sub'         => null,
    'createRoute' => null,
    'createLabel' => 'Tambah Data',
])

<div class="flex items-start justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-slate-800" style="font-family: 'Sora', sans-serif;">{{ $title }}</h2>
        @if($sub)
            <p class="text-sm text-slate-400 mt-0.5">{{ $sub }}</p>
        @endif
    </div>
    @if($createRoute)
        <a href="{{ route($createRoute) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-500
                  text-white text-sm font-semibold rounded-xl transition-all shadow-sm shadow-blue-600/20">
            @include('components.ui.icons', ['icon' => 'plus', 'class' => 'w-4 h-4'])
            {{ $createLabel }}
        </a>
    @endif
</div>