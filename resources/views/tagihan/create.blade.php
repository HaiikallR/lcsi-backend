@extends('layouts.app')
@section('title', 'Buat Tagihan')
@section('content')
<x-ui.form-card title="Buat Tagihan Baru" :back-route="route('tagihan.index')">
    <form method="POST" action="{{ route('tagihan.store') }}" id="form">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Pelanggan <span class="text-red-400">*</span></label>
                <select name="id_pelanggan" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Pelanggan...</option>
                    @foreach($pelanggans as $p)
                    <option value="{{ $p->id }}" @selected(old('id_pelanggan') == $p->id)>{{ $p->nama }} — {{ $p->paket_langganan }}</option>
                    @endforeach
                </select>
                @error('id_pelanggan')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <x-ui.input name="jumlah" label="Jumlah (Rp)" type="number" required />
            <x-ui.input name="bulan"  label="Bulan" required help="Contoh: Januari" />
            <x-ui.input name="tahun"  label="Tahun" type="number" :value="date('Y')" required />
            <x-ui.select name="status" label="Status"
                :options="['belum bayar' => 'Belum Bayar', 'menunggu' => 'Menunggu', 'sudah bayar' => 'Sudah Bayar']"
                selected="belum bayar" />
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Catatan</label>
                <textarea name="catatan" rows="2" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">{{ old('catatan') }}</textarea>
            </div>
        </div>
    </form>
    <x-slot:actions>
        <button form="form" type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl transition">Simpan Tagihan</button>
        <a href="{{ route('tagihan.index') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm rounded-xl transition hover:border-slate-300">Batal</a>
    </x-slot:actions>
</x-ui.form-card>
@endsection