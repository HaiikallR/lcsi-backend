@extends('layouts.app')
@section('title', 'Tambah Pelanggan')
@section('content')

<x-ui.form-card title="Tambah Pelanggan Baru" :back-route="route('pelanggan.index')">

    <form method="POST" action="{{ route('pelanggan.store') }}" id="form">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <x-ui.input
                name="nama"
                label="Nama Lengkap"
                placeholder="Contoh: Budi Santoso"
                required
            />

            <x-ui.input
                name="email"
                label="Email"
                type="email"
                placeholder="Contoh: budi@email.com"
                required
            />

            <x-ui.input
                name="no_hp"
                label="No. HP / WhatsApp"
                placeholder="Contoh: 08123456789"
                required
            />

            <x-ui.input
                name="paket_langganan"
                label="Paket Langganan"
                placeholder="Contoh: 10 Mbps, 20 Mbps"
                required
                help="Isi sesuai paket yang tersedia"
            />

            <x-ui.select
                name="status"
                label="Status"
                required
                :options="[
                    'aktif'       => 'Aktif',
                    'tidak aktif' => 'Tidak Aktif',
                ]"
                selected="aktif"
            />

            <x-ui.input
                name="password"
                label="Password"
                type="password"
                required
                help="Minimal 8 karakter"
            />

            {{-- Alamat full width --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                    Alamat <span class="text-red-400">*</span>
                </label>
                <textarea
                    name="alamat"
                    rows="3"
                    required
                    placeholder="Jl. Contoh No. 1, Kelurahan, Kecamatan, Kota..."
                    class="w-full px-4 py-2.5 bg-white border rounded-xl text-sm text-slate-800
                           placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500
                           transition {{ $errors->has('alamat') ? 'border-red-300' : 'border-slate-200' }}"
                >{{ old('alamat') }}</textarea>
                @error('alamat')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

        </div>
    </form>

    <x-slot:actions>
        <button form="form" type="submit"
                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white
                       text-sm font-semibold rounded-xl transition shadow-sm">
            Simpan Pelanggan
        </button>
        <a href="{{ route('pelanggan.index') }}"
           class="px-5 py-2.5 bg-white border border-slate-200 hover:border-slate-300
                  text-slate-600 text-sm font-medium rounded-xl transition">
            Batal
        </a>
    </x-slot:actions>

</x-ui.form-card>

@endsection