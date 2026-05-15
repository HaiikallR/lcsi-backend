@extends('layouts.app')
@section('title', 'Detail Verifikasi Pembayaran')
@section('content')

<div class="max-w-4xl">
    <a href="{{ route('verifikasi-pembayaran.index') }}"
       class="inline-flex items-center gap-2 text-sm text-slate-400 hover:text-slate-600 mb-5 transition">
        @include('components.ui.icons', ['icon' => 'chevron-left', 'class' => 'w-4 h-4'])
        Kembali ke Daftar
    </a>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        {{-- Kiri: Info Pembayaran --}}
        <div class="space-y-4">

            {{-- Info Pelanggan --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <h3 class="font-semibold text-slate-800 mb-4 text-sm uppercase tracking-wide text-slate-400">
                    Data Pelanggan
                </h3>
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <span class="text-blue-600 text-lg font-bold">
                            {{ strtoupper(substr($verifikasiPembayaran->pelanggan->nama, 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800">{{ $verifikasiPembayaran->pelanggan->nama }}</p>
                        <p class="text-sm text-slate-400">{{ $verifikasiPembayaran->pelanggan->email }}</p>
                    </div>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-slate-400">No. HP</span>
                        <span class="font-medium text-slate-700">{{ $verifikasiPembayaran->pelanggan->no_hp }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-slate-400">Paket</span>
                        <span class="font-medium text-slate-700">{{ $verifikasiPembayaran->pelanggan->paket_langganan }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-slate-400">Status</span>
                        <x-ui.badge :status="$verifikasiPembayaran->pelanggan->status" />
                    </div>
                </div>
            </div>

            {{-- Info Tagihan --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <h3 class="font-semibold text-slate-800 mb-4 text-sm uppercase tracking-wide text-slate-400">
                    Detail Tagihan
                </h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-slate-400">Periode</span>
                        <span class="font-medium text-slate-700">
                            {{ $verifikasiPembayaran->bulan }} {{ $verifikasiPembayaran->tahun }}
                        </span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-slate-400">Nominal</span>
                        <span class="font-bold text-slate-800 text-base">
                            Rp {{ number_format($verifikasiPembayaran->tagihan->jumlah, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-slate-400">Status Tagihan</span>
                        <x-ui.badge :status="$verifikasiPembayaran->tagihan->status" />
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-slate-400">Dikirim Pada</span>
                        <span class="text-slate-600">{{ $verifikasiPembayaran->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-slate-400">Status Verifikasi</span>
                        <x-ui.badge :status="$verifikasiPembayaran->status" />
                    </div>
                </div>

                {{-- Catatan penolakan --}}
                @if($verifikasiPembayaran->keterangan)
                <div class="mt-4 p-3 bg-red-50 border border-red-100 rounded-xl">
                    <p class="text-xs font-semibold text-red-600 mb-1">Alasan Penolakan:</p>
                    <p class="text-sm text-red-600">{{ $verifikasiPembayaran->keterangan }}</p>
                </div>
                @endif
            </div>

            {{-- Tombol Aksi --}}
            @if($verifikasiPembayaran->status === 'menunggu')
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 space-y-3">
                <h3 class="font-semibold text-slate-800 text-sm uppercase tracking-wide text-slate-400">
                    Tindakan
                </h3>

                {{-- Tombol Setujui --}}
                <form method="POST" action="{{ route('verifikasi-pembayaran.setujui', $verifikasiPembayaran) }}"
                      x-data @submit.prevent="if(confirm('Setujui pembayaran ini? Status tagihan akan berubah menjadi Sudah Bayar.')) $el.submit()">
                    @csrf
                    <button type="submit"
                            class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm
                                   font-semibold rounded-xl transition inline-flex items-center justify-center gap-2">
                        @include('components.ui.icons', ['icon' => 'check-circle', 'class' => 'w-4 h-4'])
                        Setujui Pembayaran
                    </button>
                </form>

                {{-- Form Tolak --}}
                <div x-data="{ open: false }">
                    <button @click="open = !open"
                            class="w-full py-2.5 bg-red-50 hover:bg-red-100 text-red-600 text-sm
                                   font-semibold rounded-xl transition inline-flex items-center justify-center gap-2">
                        @include('components.ui.icons', ['icon' => 'x-circle', 'class' => 'w-4 h-4'])
                        Tolak Pembayaran
                    </button>

                    <div x-show="open" x-transition class="mt-3">
                        <form method="POST" action="{{ route('verifikasi-pembayaran.tolak', $verifikasiPembayaran) }}">
                            @csrf
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">
                                        Alasan Penolakan <span class="text-red-400">*</span>
                                    </label>
                                    <textarea name="keterangan" rows="3" required
                                              class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl
                                                     text-sm focus:outline-none focus:ring-2 focus:ring-red-500 transition"
                                              placeholder="Contoh: Bukti transfer tidak jelas, nominal tidak sesuai...">{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit"
                                        class="w-full py-2.5 bg-red-600 hover:bg-red-500 text-white text-sm
                                               font-semibold rounded-xl transition">
                                    Konfirmasi Penolakan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- Kanan: Bukti Transfer --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <h3 class="font-semibold text-slate-800 mb-4 text-sm uppercase tracking-wide text-slate-400">
                Bukti Transfer
            </h3>

            @if($verifikasiPembayaran->bukti_transfer)
                @php
                    $ext = pathinfo($verifikasiPembayaran->bukti_transfer, PATHINFO_EXTENSION);
                    $isPdf = strtolower($ext) === 'pdf';
                @endphp

                @if($isPdf)
                    {{-- Tampilan untuk PDF --}}
                    <div class="flex flex-col items-center justify-center p-8 bg-slate-50 rounded-xl border border-slate-200">
                        <svg class="w-12 h-12 text-red-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm font-medium text-slate-600 mb-3">File PDF</p>
                        <a href="{{ Storage::url($verifikasiPembayaran->bukti_transfer) }}"
                           target="_blank"
                           class="px-4 py-2 bg-blue-600 text-white text-sm rounded-xl hover:bg-blue-500 transition">
                            Buka PDF
                        </a>
                    </div>
                @else
                    {{-- Tampilan untuk Gambar --}}
                    <div class="space-y-3">
                        <img src="{{ Storage::url($verifikasiPembayaran->bukti_transfer) }}"
                             alt="Bukti Transfer"
                             class="w-full rounded-xl border border-slate-200 object-contain max-h-96">
                        <a href="{{ Storage::url($verifikasiPembayaran->bukti_transfer) }}"
                           target="_blank"
                           class="w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium
                                  rounded-xl transition inline-flex items-center justify-center gap-2">
                            @include('components.ui.icons', ['icon' => 'eye', 'class' => 'w-4 h-4'])
                            Lihat Ukuran Penuh
                        </a>
                    </div>
                @endif
            @else
                <div class="flex flex-col items-center justify-center py-12 text-slate-300">
                    <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm">Tidak ada bukti transfer</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection