@extends('layouts.app')
@section('title', 'Edit Teknisi')
@section('content')
<x-ui.form-card title="Edit Teknisi" :back-route="route('teknisi.index')">
    <form method="POST" action="{{ route('teknisi.update', $teknisi) }}" id="form">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <x-ui.input name="nama"  label="Nama Teknisi" :value="$teknisi->nama" required />
            <x-ui.input name="no_hp" label="No. HP" :value="$teknisi->no_hp" required />
            <x-ui.select name="status" label="Status" required
                :options="['aktif' => 'Aktif',  'tidak aktif' => 'Tidak Aktif']"
                :selected="$teknisi->status" />
        </div>
    </form>
    <x-slot:actions>
        <button form="form" type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl transition">Simpan Perubahan</button>
        <a href="{{ route('teknisi.index') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm rounded-xl transition hover:border-slate-300">Batal</a>
    </x-slot:actions>
</x-ui.form-card>
@endsection