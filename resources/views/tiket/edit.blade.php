@extends('layouts.app')
@section('title', 'Edit Tiket')
@section('content')
<x-ui.form-card title="Edit Tiket #{{ $tiket->id }}" :back-route="route('tiket.index')">
    <form method="POST" action="{{ route('tiket.update', $tiket) }}" id="form">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <x-ui.input name="jenis_pekerjaan" label="Jenis Pekerjaan" :value="$tiket->jenis_pekerjaan" required />
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Teknisi <span class="text-red-400">*</span></label>
                <select name="id_teknisi" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($teknisis as $t)
                    <option value="{{ $t->id }}" @selected(old('id_teknisi', $tiket->id_teknisi) == $t->id)>{{ $t->nama }}</option>
                    @endforeach
                </select>
            </div>
            <x-ui.input name="ongkos_teknisi" label="Ongkos Teknisi (Rp)" type="number" :value="$tiket->ongkos_teknisi" />
            <x-ui.select name="status" label="Status" required
                :options="['in progress' => 'In Progress', 'selesai' => 'Selesai']"
                :selected="$tiket->status" />
        </div>
    </form>
    <x-slot:actions>
        <button form="form" type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl transition">Simpan Perubahan</button>
        <a href="{{ route('tiket.index') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm rounded-xl transition hover:border-slate-300">Batal</a>
    </x-slot:actions>
</x-ui.form-card>
@endsection