{{--
    components/layout/navbar.blade.php
    ─────────────────────────────────────────────────────────────────────────
    Prop $title tersedia dari Navbar.php
--}}
<header class="bg-white border-b border-slate-100 px-6 py-3.5 flex items-center justify-between flex-shrink-0 z-10">
    <div class="flex items-center gap-4">
        {{-- Toggle Sidebar Button (Alpine.js) --}}
        <button @click="sidebarOpen = !sidebarOpen"
                class="p-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        {{-- Breadcrumb / Judul --}}
        <div>
            <h1 class="font-display text-base font-semibold text-slate-800" style="font-family: 'Sora', sans-serif;">
                {{ $title }}
            </h1>
        </div>
    </div>

    {{-- Kanan: Tanggal --}}
    <div class="flex items-center gap-3">
        <span class="text-xs text-slate-400 hidden sm:block">
            {{ now()->translatedFormat('l, d F Y') }}
        </span>
    </div>
</header>