{{--
    components/ui/form-card.blade.php
    ─────────────────────────────────────────────────────────────────────────
    ANONYMOUS BLADE COMPONENT dengan SLOTS

    Slot adalah area konten yang bisa diisi oleh parent.
    • $slot          → konten utama (default slot)
    • $actions       → slot untuk tombol submit/batal

    Contoh pemakaian:
    <x-ui.form-card title="Tambah Pelanggan" :back-route="route('pelanggan.index')">
        <div>... form fields ...</div>

        <x-slot:actions>
            <button type="submit">Simpan</button>
        </x-slot:actions>
    </x-ui.form-card>
--}}
@props([
    'title',
    'backRoute' => null,
])

<div class="max-w-3xl">
    {{-- Back Button --}}
    @if($backRoute)
    <a href="{{ $backRoute }}"
       class="inline-flex items-center gap-2 text-sm text-slate-400 hover:text-slate-600 mb-5 transition group">
        @include('components.ui.icons', ['icon' => 'chevron-left', 'class' => 'w-4 h-4 group-hover:-translate-x-0.5 transition-transform'])
        Kembali 
    </a>
    @endif

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-50">
            <h3 class="font-semibold text-slate-800" style="font-family: 'Sora', sans-serif;">{{ $title }}</h3>
        </div>
        <div class="p-6 space-y-5">
            {{ $slot }}
        </div>
        @isset($actions)
        <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex items-center gap-3">
            {{ $actions }}
        </div>
        @endisset
    </div>
</div>