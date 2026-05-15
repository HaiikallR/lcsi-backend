@extends('layouts.app')
@section('title', 'Edit Pelanggan')
@section('content')

<x-ui.form-card title="Edit Data Pelanggan" :back-route="route('pelanggan.index')">

    <form method="POST" action="{{ route('pelanggan.update', $pelanggan) }}" id="form">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <x-ui.input
                name="nama"
                label="Nama Lengkap"
                :value="$pelanggan->nama"
                required
            />

            <x-ui.input
                name="email"
                label="Email"
                type="email"
                :value="$pelanggan->email"
                required
            />

            <x-ui.input
                name="no_hp"
                label="No. HP / WhatsApp"
                :value="$pelanggan->no_hp"
                required
            />

            <x-ui.input
                name="paket_langganan"
                label="Paket Langganan"
                :value="$pelanggan->paket_langganan"
                required
            />

            <x-ui.select
                name="status"
                label="Status"
                required
                :options="[
                    'aktif'       => 'Aktif',
                    'tidak aktif' => 'Tidak Aktif',
                ]"
                :selected="$pelanggan->status"
            />

            <x-ui.input
                name="password"
                label="Password Baru"
                type="password"
                help="Kosongkan jika tidak ingin mengubah password"
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
                    class="w-full px-4 py-2.5 bg-white border rounded-xl text-sm text-slate-800
                           placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500
                           transition {{ $errors->has('alamat') ? 'border-red-300' : 'border-slate-200' }}"
                >{{ old('alamat', $pelanggan->alamat) }}</textarea>
                @error('alamat')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

        </div>

        {{-- Info pelanggan saat ini --}}
        <div class="mt-2 p-4 bg-slate-50 rounded-xl border border-slate-100">
            <p class="text-xs text-slate-400 mb-2 font-medium uppercase tracking-wide">Info Akun</p>
            <div class="grid grid-cols-2 gap-3 text-xs">
                <div>
                    <span class="text-slate-400">Terdaftar:</span>
                    <span class="text-slate-600 ml-1">{{ $pelanggan->created_at->format('d M Y') }}</span>
                </div>
                <div>
                    <span class="text-slate-400">Terakhir diupdate:</span>
                    <span class="text-slate-600 ml-1">{{ $pelanggan->updated_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>

    </form>

    <x-slot:actions>
        <button form="form" type="submit"
                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white
                       text-sm font-semibold rounded-xl transition shadow-sm">
            Simpan Perubahan
        </button>
        <a href="{{ route('pelanggan.index') }}"
           class="px-5 py-2.5 bg-white border border-slate-200 hover:border-slate-300
                  text-slate-600 text-sm font-medium rounded-xl transition">
            Batal
        </a>
        {{-- Shortcut ke halaman detail --}}
        <a href="{{ route('pelanggan.show', $pelanggan) }}"
           class="ml-auto px-4 py-2.5 text-slate-400 hover:text-slate-600
                  text-sm transition inline-flex items-center gap-1.5">
            @include('components.ui.icons', ['icon' => 'eye', 'class' => 'w-4 h-4'])
            Lihat Detail
        </a>
    </x-slot:actions>

</x-ui.form-card>

@endsection