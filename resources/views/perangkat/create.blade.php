@extends('layouts.app')
@section('title', 'Tambah Perangkat')
@section('content')
<x-ui.form-card title="Tambah Perangkat Baru" :back-route="route('perangkat.index')">
    <form method="POST" action="{{ route('perangkat.store') }}" id="form">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <x-ui.input name="nama_perangkat" label="Nama Perangkat" required
                        help="Contoh: Router WiFi, ONT, Switch" />

            <x-ui.input name="merk" label="Merk / Brand" required
                        help="Contoh: TP-Link, Huawei, Mikrotik" />

            <x-ui.input name="serial_number" label="Serial Number" required
                        help="Nomor unik perangkat" />

            <x-ui.select name="status" label="Status"
                :options="['tersedia' => 'Tersedia', 'digunakan' => 'Digunakan', 'perbaikan' => 'Perbaikan']"
                selected="tersedia" />

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                    Pelanggan <span class="text-red-400">*</span>
                    <span class="text-slate-400 font-normal ml-1">(1 perangkat per pelanggan)</span>
                </label>
                <select name="id_pelanggan" required
                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Pelanggan...</option>
                    @foreach($pelanggans as $p)
                        <option value="{{ $p->id }}" @selected(old('id_pelanggan') == $p->id)>
                            {{ $p->nama }} — {{ $p->paket_langganan }}
                        </option>
                    @endforeach
                </select>
                @error('id_pelanggan')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

        </div>
    </form>
    <x-slot:actions>
        <button form="form" type="submit"
                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl transition">
            Simpan Perangkat
        </button>
        <a href="{{ route('perangkat.index') }}"
           class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm rounded-xl transition hover:border-slate-300">
            Batal
        </a>
    </x-slot:actions>
</x-ui.form-card>
@endsection