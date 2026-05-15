@extends('layouts.app')
@section('title', 'Detail Tiket #' . $tiket->id)
@section('content')
<div class="max-w-3xl">
    <a href="{{ route('tiket.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-400 hover:text-slate-600 mb-5 transition">
        @include('components.ui.icons', ['icon' => 'chevron-left', 'class' => 'w-4 h-4'])
        Kembali
    </a>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-800" style="font-family: 'Sora', sans-serif;">Tiket #{{ $tiket->id }}</h2>
                <p class="text-slate-400 text-sm mt-1">{{ $tiket->jenis_pekerjaan }}</p>
            </div>
            <div class="flex items-center gap-2">
                <x-ui.badge :status="$tiket->status" />
                <a href="{{ route('tiket.edit', $tiket) }}" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition">
                    @include('components.ui.icons', ['icon' => 'edit', 'class' => 'w-4 h-4'])
                </a>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Pelanggan</p>
                <p class="text-sm font-medium text-slate-700">
                    {{ $tiket->pelanggan?->nama ?? $tiket->calon_pelanggan_nama ?? '-' }}
                    @if(!$tiket->pelanggan && $tiket->calon_pelanggan_nama)
                        <span class="text-amber-500 text-xs ml-1">(Calon Pelanggan)</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Teknisi</p>
                <p class="text-sm font-medium text-slate-700">{{ $tiket->teknisi?->nama ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Perkiraan Ongkos Teknisi</p>
                <p class="text-sm font-medium text-slate-700">Rp {{ number_format($tiket->ongkos_teknisi, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Dibuat</p>
                <p class="text-sm font-medium text-slate-700">{{ $tiket->created_at->format('d M Y H:i') }}</p>
            </div>
            @if($tiket->tanggal_selesai)
            <div>
                <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Selesai</p>
                <p class="text-sm font-medium text-slate-700">{{ $tiket->tanggal_selesai->format('d M Y H:i') }}</p>
            </div>
            @endif
            @if($tiket->pengeluaran)
            <div class="col-span-2 pt-4 border-t border-slate-50">
                <p class="text-xs text-slate-400 uppercase tracking-wide mb-2">Pengeluaran Terkait</p>
                <div class="bg-slate-50 rounded-xl p-4">
                    <div class="flex justify-between">
                        <div>
                            <p class="font-medium text-slate-700">{{ $tiket->pengeluaran->judul }}</p>
                            <p class="text-xs text-slate-400">{{ $tiket->pengeluaran->kategori }} • {{ $tiket->pengeluaran->bulan }} {{ $tiket->pengeluaran->tahun }}</p>
                        </div>
                        <p class="font-bold text-slate-800">Rp {{ number_format($tiket->pengeluaran->jumlah, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection