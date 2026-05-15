@extends('layouts.app')
@section('title', 'Edit Pemasukan')
@section('content')
<x-ui.form-card title="Edit Data Pemasukan" :back-route="route('pemasukan.index')">
    <form method="POST" action="{{ route('pemasukan.update', $pemasukan) }}" id="form" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <x-ui.input name="jenis_pemasukan" label="Jenis Pemasukan" :value="$pemasukan->jenis_pemasukan" required />
            <x-ui.input name="jumlah_bayar"    label="Jumlah Bayar (Rp)" type="number" :value="$pemasukan->jumlah_bayar" required />

            <x-ui.select name="metode_bayar" label="Metode Pembayaran" required
                :options="['Transfer Bank' => 'Transfer Bank', 'Tunai' => 'Tunai', 'QRIS' => 'QRIS', 'Lainnya' => 'Lainnya']"
                :selected="$pemasukan->metode_bayar" />

            <x-ui.select name="status" label="Status"
                :options="['menunggu' => 'Menunggu', 'lunas' => 'Lunas']"
                :selected="$pemasukan->status" />

            <x-ui.input name="bulan_tagihan" label="Bulan Tagihan" :value="$pemasukan->bulan_tagihan" required />
            <x-ui.input name="tahun_tagihan" label="Tahun Tagihan" type="number" :value="$pemasukan->tahun_tagihan" required />
            <x-ui.input name="tanggal_bayar" label="Tanggal Bayar" type="datetime-local"
                        :value="$pemasukan->tanggal_bayar?->format('Y-m-d\TH:i')" />

            {{-- Bukti bayar yang sudah ada --}}
            @if($pemasukan->bukti_bayar)
            <div class="md:col-span-2">
                <p class="text-sm font-medium text-slate-700 mb-2">Bukti Saat Ini</p>
                <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-sm text-slate-600 flex-1">{{ $pemasukan->bukti_bayar }}</span>
                </div>
            </div>
            @endif

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                    {{ $pemasukan->bukti_bayar ? 'Ganti Bukti Pembayaran' : 'Bukti Pembayaran' }}
                </label>
                <input type="file" name="bukti_bayar" accept="image/*,.pdf"
                       class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm
                              file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0
                              file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700
                              hover:file:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Keterangan</label>
                <textarea name="keterangan" rows="2"
                          class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm
                                 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">{{ old('keterangan', $pemasukan->keterangan) }}</textarea>
            </div>
        </div>
    </form>
    <x-slot:actions>
        <button form="form" type="submit"
                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl transition">
            Simpan Perubahan
        </button>
        <a href="{{ route('pemasukan.index') }}"
           class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm rounded-xl transition hover:border-slate-300">
            Batal
        </a>
    </x-slot:actions>
</x-ui.form-card>
@endsection