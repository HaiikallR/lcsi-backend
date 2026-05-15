@extends('layouts.app')
@section('title', 'Edit Tagihan')
@section('content')
<x-ui.form-card title="Edit Tagihan" :back-route="route('tagihan.index')">
    <form method="POST" action="{{ route('tagihan.update', $tagihan) }}" id="form">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <x-ui.input name="jumlah" label="Jumlah (Rp)" type="number" :value="$tagihan->jumlah" required />
            <x-ui.input name="bulan"  label="Bulan" :value="$tagihan->bulan" required />
            <x-ui.input name="tahun"  label="Tahun" type="number" :value="$tagihan->tahun" required />
            <x-ui.select name="status" label="Status"
                :options="['belum bayar' => 'Belum Bayar', 'menunggu' => 'Menunggu', 'sudah bayar' => 'Sudah Bayar']"
                :selected="$tagihan->status" />
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Catatan</label>
                <textarea name="catatan" rows="2" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">{{ old('catatan', $tagihan->catatan) }}</textarea>
            </div>
        </div>
    </form>
    <x-slot:actions>
        <button form="form" type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl transition">Simpan Perubahan</button>
        <a href="{{ route('tagihan.index') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm rounded-xl transition hover:border-slate-300">Batal</a>
    </x-slot:actions>
</x-ui.form-card>
@endsection