@extends('layouts.app')
@section('title', 'Edit Perangkat')
@section('content')
<x-ui.form-card title="Edit Perangkat" :back-route="route('perangkat.index')">
    <form method="POST" action="{{ route('perangkat.update', $perangkat) }}" id="form">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <x-ui.input name="nama_perangkat" label="Nama Perangkat" :value="$perangkat->nama_perangkat" required />
            <x-ui.input name="merk"           label="Merk / Brand"   :value="$perangkat->merk" required />
            <x-ui.input name="serial_number"  label="Serial Number"  :value="$perangkat->serial_number" required />
            <x-ui.select name="status" label="Status"
                :options="['tersedia' => 'Tersedia', 'digunakan' => 'Digunakan', 'perbaikan' => 'Perbaikan']"
                :selected="$perangkat->status" />
        </div>
    </form>
    <x-slot:actions>
        <button form="form" type="submit"
                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl transition">
            Simpan Perubahan
        </button>
        <a href="{{ route('perangkat.index') }}"
           class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm rounded-xl transition hover:border-slate-300">
            Batal
        </a>
    </x-slot:actions>
</x-ui.form-card>
@endsection