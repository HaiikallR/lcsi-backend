@extends('layouts.app')
@section('title', 'Buat Tiket')
@section('content')
<x-ui.form-card title="Buat Tiket Baru" :back-route="route('tiket.index')">
    <form method="POST" action="{{ route('tiket.store') }}" id="form" x-data="{ isPelanggan: true }">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <x-ui.input name="jenis_pekerjaan" label="Jenis Pekerjaan" required
                        help="Contoh: Pasang Baru, Gangguan, Relokasi" />

            {{-- Toggle Pelanggan vs Calon --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-2">Tipe Pelanggan</label>
                <div class="flex gap-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" x-model.boolean="isPelanggan" :value="true" class="text-blue-600">
                        <span class="text-sm text-slate-600">Pelanggan Terdaftar</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" x-model.boolean="isPelanggan" :value="false" class="text-blue-600">                       
                     <span class="text-sm text-slate-600">Calon Pelanggan</span>
                    </label>
                </div>
            </div>

            {{-- Pelanggan terdaftar --}}
            <div x-show="isPelanggan" class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Pelanggan</label>
                <select name="id_pelanggan" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Pelanggan...</option>
                    @foreach($pelanggans as $p)
                    <option value="{{ $p->id }}" @selected(old('id_pelanggan') == $p->id)>{{ $p->nama }} — {{ $p->paket_langganan }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Calon pelanggan --}}
            <div x-show="!isPelanggan" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-ui.input name="calon_pelanggan_nama"  label="Nama Calon Pelanggan" />
                <x-ui.input name="calon_pelanggan_no_hp" label="No. HP Calon Pelanggan" />
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Alamat Calon Pelanggan</label>
                    <textarea name="calon_pelanggan_alamat" rows="2"
                              class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">{{ old('calon_pelanggan_alamat') }}</textarea>
                </div>
            </div>

            {{-- Teknisi --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Teknisi <span class="text-red-400">*</span></label>
                <select name="id_teknisi" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Teknisi...</option>
                    @foreach($teknisis as $t)
                    <option value="{{ $t->id }}" @selected(old('id_teknisi') == $t->id)>{{ $t->nama }} ({{ $t->status }})</option>
                    @endforeach
                </select>
                @error('id_teknisi')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <x-ui.input name="ongkos_teknisi" label="Perkiraan Ongkos Teknisi (Rp)" type="number" value="0" />
        </div>
    </form>
    <x-slot:actions>
        <button form="form" type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl transition">Buat Tiket</button>
        <a href="{{ route('tiket.index') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm rounded-xl transition hover:border-slate-300">Batal</a>
    </x-slot:actions>
</x-ui.form-card>
@endsection