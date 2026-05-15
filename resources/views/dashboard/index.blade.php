{{--
    dashboard/index.blade.php
    ─────────────────────────────────────────────────────────────────────────
    LAYOUT INHERITANCE:
    • @extends('layouts.app')       → pakai layout utama
    • @section('title', '...')      → isi slot judul
    • @section('content') ... @endsection → isi slot konten

    BLADE COMPONENTS yang dipakai:
    • <x-ui.stat-card>  → kartu statistik (reusable)
    • <x-ui.badge>      → badge status (reusable)
--}}
@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

{{-- ── Kartu Statistik ─────────────────────────────── --}}
<div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">

    {{--
        BLADE COMPONENT — <x-ui.stat-card>
        Props dikirim langsung dari tag komponen.
        :value pakai titik dua karena nilainya variabel PHP.
    --}}
    <x-ui.stat-card
        label="Total Pelanggan" 
        :value="$statistik['total_pelanggan']"
        icon="users"
        color="blue"
    />
    <x-ui.stat-card
        label="Tiket Aktif"
        :value="$statistik['tiket_aktif']"
        icon="ticket"
        color="yellow"
    />
    <x-ui.stat-card
        label="Tagihan Belum Bayar"
        :value="$statistik['tagihan_belum_bayar']"
        icon="file-text"
        color="red"
    />
    <x-ui.stat-card
        label="Total Pemasukan"
        :value="$statistik['total_pemasukan']"
        icon="trending-up"
        color="green"
        prefix="Rp"
    />
    <x-ui.stat-card
        label="Total Pengeluaran"
        :value="$statistik['total_pengeluaran']"
        icon="trending-down"
        color="orange"
        prefix="Rp"
    />
</div>

{{-- ── Baris Kedua: Tiket Terbaru + Ringkasan ─────── --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    {{-- Tiket Terbaru (2/3 lebar) --}}
    <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-50">
            <h3 class="font-display font-semibold text-slate-800" style="font-family: 'Sora', sans-serif;">
                Tiket Terbaru
            </h3>
            <a href="{{ route('tiket.index') }}"
               class="text-xs text-blue-600 hover:text-blue-700 font-medium transition">
                Lihat Semua →
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-50 bg-slate-50/50">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Jenis Pekerjaan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Teknisi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($tiket_terbaru as $tiket)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-3.5 font-medium text-slate-700">
                            {{ $tiket->pelanggan?->nama ?? $tiket->calon_pelanggan_nama ?? '-' }}
                        </td>
                        <td class="px-6 py-3.5 text-slate-500">{{ $tiket->jenis_pekerjaan }}</td>
                        <td class="px-6 py-3.5 text-slate-500">{{ $tiket->teknisi?->nama ?? '-' }}</td>
                        <td class="px-6 py-3.5">
                            {{-- BLADE COMPONENT — <x-ui.badge> --}}
                            <x-ui.badge :status="$tiket->status" />
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-slate-300 text-sm">
                            Belum ada tiket
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Panel Kanan (1/3 lebar) --}}
    <div class="space-y-4">

        {{-- Tagihan Terbaru --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-50">
                <h3 class="font-semibold text-slate-800 text-sm" style="font-family: 'Sora', sans-serif;">Tagihan Terbaru</h3>
                <a href="{{ route('tagihan.index') }}" class="text-xs text-blue-600 hover:text-blue-700 font-medium">Lihat →</a>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($tagihan_terbaru as $tagihan)
                <div class="px-5 py-3 flex items-center justify-between">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-slate-700 truncate">{{ $tagihan->pelanggan?->nama }}</p>
                        <p class="text-xs text-slate-400">{{ $tagihan->bulan }} {{ $tagihan->tahun }}</p>
                    </div>
                    <x-ui.badge :status="$tagihan->status" />
                </div>
                @empty
                <p class="px-5 py-4 text-xs text-slate-300 text-center">Tidak ada data</p>
                @endforelse
            </div>
        </div>

        {{-- Upgrade Menunggu --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-50">
                <h3 class="font-semibold text-slate-800 text-sm" style="font-family: 'Sora', sans-serif;">Permintaan Upgrade</h3>
                <a href="{{ route('permintaan-upgrade.index') }}" class="text-xs text-blue-600 hover:text-blue-700 font-medium">Lihat →</a>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($upgrade_terbaru as $upgrade)
                <div class="px-5 py-3">
                    <p class="text-sm font-medium text-slate-700 truncate">{{ $upgrade->pelanggan?->nama }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xs text-slate-400">{{ $upgrade->paket_lama }}</span>
                        <span class="text-xs text-slate-300">→</span>
                        <span class="text-xs font-medium text-blue-600">{{ $upgrade->paket_baru }}</span>
                    </div>
                </div>
                @empty
                <p class="px-5 py-4 text-xs text-slate-300 text-center">Tidak ada permintaan</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection