@extends('layouts.app')
@section('title', 'Detail Permintaan Upgrade')
@section('content')

<div class="max-w-2xl">
    <a href="{{ route('permintaan-upgrade.index') }}"
       class="inline-flex items-center gap-2 text-sm text-slate-400 hover:text-slate-600 mb-5 transition">
        @include('components.ui.icons', ['icon' => 'chevron-left', 'class' => 'w-4 h-4'])
        Kembali ke Daftar
    </a>

    <div class="space-y-4">

        {{-- Info Pelanggan --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-xs text-slate-400 uppercase tracking-wide font-semibold mb-4">Data Pelanggan</p>
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <span class="text-blue-600 text-lg font-bold">
                        {{ strtoupper(substr($permintaanUpgrade->pelanggan->nama, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <p class="font-semibold text-slate-800">{{ $permintaanUpgrade->pelanggan->nama }}</p>
                    <p class="text-sm text-slate-400">{{ $permintaanUpgrade->pelanggan->email }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3 text-sm border-t border-slate-50 pt-4">
                <div>
                    <p class="text-xs text-slate-400 mb-1">No. HP</p>
                    <p class="font-medium text-slate-700">{{ $permintaanUpgrade->pelanggan->no_hp }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 mb-1">Paket Saat Ini</p>
                    <p class="font-medium text-slate-700">{{ $permintaanUpgrade->pelanggan->paket_langganan }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 mb-1">Status Akun</p>
                    <x-ui.badge :status="$permintaanUpgrade->pelanggan->status" />
                </div>
            </div>
        </div>

        {{-- Detail Permintaan --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-xs text-slate-400 uppercase tracking-wide font-semibold mb-4">Detail Permintaan</p>

            {{-- Visualisasi upgrade paket --}}
            <div class="flex items-center justify-center gap-4 py-4 mb-4 bg-slate-50 rounded-xl">
                <div class="text-center">
                    <p class="text-xs text-slate-400 mb-2">Paket Lama</p>
                    <span class="px-4 py-2 bg-slate-200 text-slate-700 font-semibold rounded-xl text-sm">
                        {{ $permintaanUpgrade->paket_lama }}
                    </span>
                </div>
                <div class="text-slate-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
                <div class="text-center">
                    <p class="text-xs text-slate-400 mb-2">Paket Baru</p>
                    <span class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-xl text-sm">
                        {{ $permintaanUpgrade->paket_baru }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <p class="text-xs text-slate-400 mb-1">Harga Baru</p>
                    <p class="font-bold text-slate-800 text-lg">
                        Rp {{ number_format($permintaanUpgrade->harga_baru, 0, ',', '.') }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 mb-1">Status</p>
                    <x-ui.badge :status="$permintaanUpgrade->status" />
                </div>
                <div>
                    <p class="text-xs text-slate-400 mb-1">Tanggal Pengajuan</p>
                    <p class="text-slate-600">{{ $permintaanUpgrade->created_at->format('d M Y H:i') }}</p>
                </div>
                @if($permintaanUpgrade->disetujui_pada)
                <div>
                    <p class="text-xs text-slate-400 mb-1">Disetujui Pada</p>
                    <p class="text-emerald-600 font-medium">{{ $permintaanUpgrade->disetujui_pada->format('d M Y H:i') }}</p>
                </div>
                @endif
                @if($permintaanUpgrade->ditolak_pada)
                <div>
                    <p class="text-xs text-slate-400 mb-1">Ditolak Pada</p>
                    <p class="text-red-600 font-medium">{{ $permintaanUpgrade->ditolak_pada->format('d M Y H:i') }}</p>
                </div>
                @endif
            </div>

            {{-- Alasan penolakan --}}
            @if($permintaanUpgrade->catatan)
            <div class="mt-4 p-4 bg-red-50 border border-red-100 rounded-xl">
                <p class="text-xs font-semibold text-red-500 uppercase tracking-wide mb-1">
                    Alasan Penolakan
                </p>
                <p class="text-sm text-red-700">{{ $permintaanUpgrade->catatan }}</p>
            </div>
            @endif
        </div>

        {{-- Tombol Aksi --}}
        @if($permintaanUpgrade->status === 'menunggu')
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5"
             x-data="{ showTolak: false }">

            <p class="text-xs text-slate-400 uppercase tracking-wide font-semibold mb-4">Tindakan Admin</p>

            <div class="space-y-3">
                {{-- Setujui --}}
                <form method="POST" action="{{ route('permintaan-upgrade.setujui', $permintaanUpgrade) }}"
                      x-data @submit.prevent="if(confirm('Setujui permintaan upgrade ini? Paket pelanggan akan langsung diperbarui.')) $el.submit()">
                    @csrf
                    <button type="submit"
                            class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white text-sm
                                   font-semibold rounded-xl transition inline-flex items-center justify-center gap-2">
                        @include('components.ui.icons', ['icon' => 'check-circle', 'class' => 'w-4 h-4'])
                        Setujui Permintaan Upgrade
                    </button>
                </form>

                {{-- Tolak --}}
                <button @click="showTolak = !showTolak"
                        class="w-full py-3 bg-red-50 hover:bg-red-100 text-red-600 text-sm
                               font-semibold rounded-xl transition inline-flex items-center justify-center gap-2">
                    @include('components.ui.icons', ['icon' => 'x-circle', 'class' => 'w-4 h-4'])
                    Tolak Permintaan
                </button>

                {{-- Form Alasan Tolak --}}
                <div x-show="showTolak"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    <form method="POST" action="{{ route('permintaan-upgrade.tolak', $permintaanUpgrade) }}">
                        @csrf
                        <div class="p-4 bg-red-50 border border-red-100 rounded-xl space-y-3">
                            <p class="text-sm font-medium text-red-700">Masukkan alasan penolakan:</p>
                            <textarea
                                name="alasan_tolak"
                                rows="4"
                                required
                                minlength="5"
                                placeholder="Jelaskan alasan penolakan dengan jelas agar pelanggan memahami..."
                                class="w-full px-4 py-2.5 bg-white border border-red-200 rounded-xl text-sm
                                       focus:outline-none focus:ring-2 focus:ring-red-500 transition resize-none"
                            ></textarea>
                            @error('alasan_tolak')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                            @enderror
                            <div class="flex gap-2">
                                <button type="submit"
                                        class="flex-1 py-2.5 bg-red-600 hover:bg-red-500 text-white
                                               text-sm font-semibold rounded-xl transition">
                                    Konfirmasi Penolakan
                                </button>
                                <button type="button" @click="showTolak = false"
                                        class="px-4 py-2.5 bg-white border border-slate-200 text-slate-600
                                               text-sm rounded-xl hover:border-slate-300 transition">
                                    Batal
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

@endsection