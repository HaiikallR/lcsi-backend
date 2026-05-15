@extends('layouts.app')
@section('title', 'Tambah Permintaan Upgrade')
@section('content')
<x-ui.form-card title="Tambah Permintaan Upgrade" :back-route="route('permintaan-upgrade.index')">
    <form method="POST" action="{{ route('permintaan-upgrade.store') }}" id="form">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Pelanggan <span class="text-red-400">*</span></label>
                <select name="id_pelanggan" required
                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Pelanggan...</option>
                    @foreach($pelanggans as $p)
                        <option value="{{ $p->id }}" @selected(old('id_pelanggan') == $p->id)>
                            {{ $p->nama }} — Paket: {{ $p->paket_langganan }}
                        </option>
                    @endforeach
                </select>
                @error('id_pelanggan')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <x-ui.input name="paket_lama"  label="Paket Lama"  required help="Contoh: 10 Mbps" />
            <x-ui.input name="paket_baru"  label="Paket Baru"  required help="Contoh: 20 Mbps" />
            <x-ui.input name="harga_baru"  label="Harga Baru (Rp)" type="number" required />

            <x-ui.select name="status" label="Status"
                :options="['menunggu' => 'Menunggu', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak']"
                selected="menunggu" />

        </div>
    </form>
    <x-slot:actions>
        <button form="form" type="submit"
                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl transition">
            Simpan Permintaan
        </button>
        <a href="{{ route('permintaan-upgrade.index') }}"
           class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm rounded-xl transition hover:border-slate-300">
            Batal
        </a>
    </x-slot:actions>
</x-ui.form-card>
@endsection