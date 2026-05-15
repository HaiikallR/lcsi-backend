@extends('layouts.app')
@section('title', 'Manajemen Notifikasi')
@section('content')

{{-- Header --}}
<div class="flex items-start justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-slate-800" style="font-family: 'Sora', sans-serif;">
            Manajemen Notifikasi
        </h2>
    </div>
    <div class="flex items-center gap-2">
        {{-- Tombol Kirim Massal --}}
        <button
            @click="$dispatch('open-massal')"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500
                   text-white text-sm font-semibold rounded-xl transition shadow-sm">
            @include('components.ui.icons', ['icon' => 'users', 'class' => 'w-4 h-4'])
            Kirim Massal
        </button>
        {{-- Tombol Kirim ke 1 Pelanggan --}}
        <a href="{{ route('notifikasi.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-500
                  text-white text-sm font-semibold rounded-xl transition shadow-sm">
            @include('components.ui.icons', ['icon' => 'bell', 'class' => 'w-4 h-4'])
            Kirim Notifikasi
        </a>
    </div>
</div>

{{-- Statistik --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <x-ui.stat-card label="Info"    :value="$statistik['info']"    icon="info"            color="blue" />
    <x-ui.stat-card label="Success" :value="$statistik['success']" icon="check-circle"   color="green" />
    <x-ui.stat-card label="Warning" :value="$statistik['warning']" icon="alert-triangle" color="yellow" />
    <x-ui.stat-card label="Error"   :value="$statistik['error']"   icon="x-circle"       color="red" />
</div>

{{-- Tabel --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50/70 border-b border-slate-100">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Pelanggan</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Judul</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Pesan</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Kategori</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Dikirim</th>
                    <th class="px-5 py-3.5 text-right text-xs font-semibold text-slate-400 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($notifikasis as $n)
                <tr class="hover:bg-slate-50/40 transition">
                    <td class="px-5 py-3.5">
                        <p class="font-medium text-slate-800">{{ $n->pelanggan?->nama ?? '-' }}</p>
                    </td>
                    <td class="px-5 py-3.5 font-medium text-slate-700">{{ $n->judul }}</td>
                    <td class="px-5 py-3.5 text-slate-500 max-w-xs">
                        <span class="line-clamp-2">{{ $n->pesan }}</span>
                    </td>
                    <td class="px-5 py-3.5">
                        @php
                            $badge = match($n->kategori) {
                                'success' => 'bg-emerald-100 text-emerald-700',
                                'warning' => 'bg-amber-100 text-amber-700',
                                'error'   => 'bg-red-100 text-red-700',
                                default   => 'bg-blue-100 text-blue-700',
                            };
                        @endphp
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badge }}">
                            {{ $n->kategori }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-slate-400 text-xs">
                        {{ $n->created_at->format('d M Y H:i') }}
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex justify-end">
                            <form method="POST" action="{{ route('notifikasi.destroy', $n) }}"
                                  x-data @submit.prevent="if(confirm('Hapus notifikasi ini?')) $el.submit()">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                    @include('components.ui.icons', ['icon' => 'trash', 'class' => 'w-4 h-4'])
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-slate-300 text-sm">
                        Belum ada notifikasi terkirim
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($notifikasis->hasPages())
    <div class="px-5 py-4 border-t border-slate-50">{{ $notifikasis->links() }}</div>
    @endif
</div>

{{-- ── Modal Kirim Massal ───────────────────────────── --}}
<div
    x-data="{
        open: false,
        init() {
            window.addEventListener('open-massal', () => this.open = true)
        }
    }"
    x-show="open"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="display:none"
>
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/40" @click="open = false"></div>

    {{-- Modal Box --}}
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-6"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">

        {{-- Header Modal --}}
        <div class="flex items-start justify-between mb-5">
            <div>
                <h3 class="font-bold text-slate-800" style="font-family: 'Sora', sans-serif;">
                    Kirim Notifikasi Massal
                </h3>
                <p class="text-sm text-slate-400 mt-1">
                    Notifikasi akan dikirim ke semua pelanggan aktif
                </p>
            </div>
            <button @click="open = false"
                    class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition">
                @include('components.ui.icons', ['icon' => 'x', 'class' => 'w-4 h-4'])
            </button>
        </div>

        {{-- Info --}}
        <div class="flex items-start gap-3 p-3 bg-amber-50 border border-amber-100 rounded-xl mb-5">
            @include('components.ui.icons', ['icon' => 'alert-triangle', 'class' => 'w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5'])
            <p class="text-sm text-amber-700">
                Notifikasi akan dikirim ke <strong>semua pelanggan aktif</strong>.
                Pelanggan tanpa device token akan di-skip.
            </p>
        </div>

        <form method="POST" action="{{ route('notifikasi.massal') }}" class="space-y-4">
            @csrf

            {{-- Judul --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                    Judul <span class="text-red-400">*</span>
                </label>
                <input type="text" name="judul" required maxlength="100"
                       placeholder="Contoh: Pengumuman Pemeliharaan Jaringan"
                       class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>

            {{-- Kategori --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                    Kategori <span class="text-red-400">*</span>
                </label>
                <select name="kategori" required
                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <option value="info">Info</option>
                    <option value="success">Success</option>
                    <option value="warning">Warning</option>
                    <option value="error">Error</option>
                </select>
            </div>

            {{-- Pesan --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                    Pesan <span class="text-red-400">*</span>
                </label>
                <textarea name="pesan" rows="4" required
                          placeholder="Tulis isi pesan notifikasi..."
                          class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm
                                 focus:outline-none focus:ring-2 focus:ring-blue-500 transition resize-none">
                </textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm
                               font-semibold rounded-xl transition inline-flex items-center justify-center gap-2">
                    @include('components.ui.icons', ['icon' => 'bell', 'class' => 'w-4 h-4'])
                    Kirim ke Semua Pelanggan
                </button>
                <button type="button" @click="open = false"
                        class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm
                               rounded-xl hover:border-slate-300 transition">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

@endsection