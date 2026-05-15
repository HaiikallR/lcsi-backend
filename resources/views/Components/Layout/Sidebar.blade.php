{{--
    components/layout/sidebar.blade.php
    ─────────────────────────────────────────────────────────────────────────
    View dari Blade Component <x-layout.sidebar>
    
    Variabel $menus tersedia otomatis dari Sidebar.php (public property).
    
    Konsep yang dipakai:
    • Alpine.js x-data / :class untuk toggle sidebar
    • @foreach untuk iterasi menu dari component class
    • request()->routeIs() untuk highlight menu aktif
--}}
<aside
    class="flex flex-col bg-slate-900 text-white transition-all duration-300 ease-in-out flex-shrink-0"
    :class="sidebarOpen ? 'w-64' : 'w-16'"
    style="font-family: 'DM Sans', sans-serif;"
>
    {{-- Logo --}}
    <div class="flex items-center gap-3 px-4 py-5 border-b border-slate-700/50">
        <div class="w-9 h-9 bg-blue-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-blue-500/30">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
        </div>
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <p class="font-display font-bold text-base leading-tight" style="font-family: 'Sora', sans-serif;">LCSI</p>
            <p class="text-slate-400 text-xs">Admin Panel</p>
        </div>
    </div>

    {{-- Navigasi --}}
    <nav class="flex-1 overflow-y-auto py-4 px-2 space-y-1">
        @foreach($menus as $group)
            {{-- Group Label --}}
            <div x-show="sidebarOpen" class="px-3 pt-3 pb-1">
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-widest">
                    {{ $group['label'] }}
                </p>
            </div>
            <div x-show="!sidebarOpen" class="border-t border-slate-700/50 my-2"></div>

            @foreach($group['items'] as $item) 
                 @php
                    $pattern = $item['pattern'] ?? $item['route'];
                    $isActive = request()->routeIs($pattern);
                @endphp
                <a href="{{ route($item['route']) }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-150 group relative
                     {{ $isActive }}
                              ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20'
                              : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}"
                   :title="!sidebarOpen ? '{{ $item['label'] }}' : ''">

                    {{-- Icon SVG --}}
                    <span class="flex-shrink-0 w-5 h-5">
                        @include('Components.ui.icons', ['icon' => $item['icon'], 'class' => 'w-5 h-5'])
                    </span>

                    {{-- Label --}}
                    <span x-show="sidebarOpen"
                          class="text-sm font-medium truncate"
                          x-transition:enter="transition-opacity duration-200"
                          x-transition:enter-start="opacity-0"
                          x-transition:enter-end="opacity-100">
                        {{ $item['label'] }}
                    </span>

                    {{-- Active indicator --}}
                    @if($isActive)
                        <span class="absolute right-2 w-1.5 h-1.5 bg-white rounded-full" x-show="sidebarOpen"></span>
                    @endif
                </a>
            @endforeach 
         @endforeach
    </nav>

    {{-- Footer: Info Admin + Logout --}}
    <div class="border-t border-slate-700/50 p-3">
        <div class="flex items-center gap-3 px-2 py-2 rounded-xl hover:bg-slate-800 transition">
            <div class="w-8 h-8 bg-blue-500/20 border border-blue-500/30 rounded-lg flex items-center justify-center flex-shrink-0">
                <span class="text-blue-400 text-xs font-bold">
                    {{ strtoupper(substr(Auth::guard('admin')->user()->nama, 0, 1)) }}
                </span>
            </div>
            <div x-show="sidebarOpen" class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">
                    {{ Auth::guard('admin')->user()->nama }}
                </p>
                <p class="text-xs text-slate-400 truncate">
                    {{ Auth::guard('admin')->user()->email }}
                </p>
            </div>
            {{-- Tombol Logout --}}
            <form method="POST" action="{{ route('logout') }}" x-show="sidebarOpen">
                @csrf
                <button type="submit" title="Logout"
                        class="p-1.5 text-slate-400 hover:text-red-400 hover:bg-red-400/10 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>