@extends('layouts.app')
@section('title', 'Tambah Teknisi')
@section('content')
<x-ui.form-card title="Tambah Teknisi Baru" :back-route="route('teknisi.index')">
    <form method="POST" action="{{ route('teknisi.store') }}" id="form">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <x-ui.input name="nama"  label="Nama Teknisi" required />
            <x-ui.input name="no_hp" label="No. HP" required />
            <x-ui.select name="status" label="Status" required
                :options="['aktif' => 'Aktif',  'tidak aktif' => 'Tidak Aktif']"
                selected="aktif" />
        </div>
    </form>
    <x-slot:actions>
        <button form="form" type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl transition">Simpan</button>
        <a href="{{ route('teknisi.index') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm rounded-xl transition hover:border-slate-300">Batal</a>
    </x-slot:actions>
</x-ui.form-card>
@endsection