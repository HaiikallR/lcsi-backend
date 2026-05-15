@extends('layouts.app')
@section('title', 'Edit Pengeluaran')
@section('content')
<x-ui.form-card title="Edit Data Pengeluaran" :back-route="route('pengeluaran.index')">
    <form method="POST" action="{{ route('pengeluaran.update', $pengeluaran) }}" id="form">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <x-ui.input name="judul"    label="Judul Pengeluaran" :value="$pengeluaran->judul" required />
            <x-ui.input name="jumlah"   label="Jumlah (Rp)" type="number" :value="$pengeluaran->jumlah" required />
            <x-ui.input name="kategori" label="Kategori" :value="$pengeluaran->kategori" required />
            <x-ui.input name="bulan"    label="Bulan" :value="$pengeluaran->bulan" required />
            <x-ui.input name="tahun"    label="Tahun" type="number" :value="$pengeluaran->tahun" required />

        </div>
    </form>
    <x-slot:actions>
        <button form="form" type="submit"
                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl transition">
            Simpan Perubahan
        </button>
        <a href="{{ route('pengeluaran.index') }}"
           class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm rounded-xl transition hover:border-slate-300">
            Batal
        </a>
    </x-slot:actions>
</x-ui.form-card>
@endsection