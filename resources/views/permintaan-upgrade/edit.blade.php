@extends('layouts.app')
@section('title', 'Edit Permintaan Upgrade')
@section('content')
<x-ui.form-card title="Edit Permintaan Upgrade" :back-route="route('permintaan-upgrade.index')">
    <form method="POST" action="{{ route('permintaan-upgrade.update', $permintaanUpgrade) }}" id="form">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <x-ui.input name="paket_lama" label="Paket Lama"      :value="$permintaanUpgrade->paket_lama" required />
            <x-ui.input name="paket_baru" label="Paket Baru"      :value="$permintaanUpgrade->paket_baru" required />
            <x-ui.input name="harga_baru" label="Harga Baru (Rp)" :value="$permintaanUpgrade->harga_baru" type="number" required />
            <x-ui.select name="status" label="Status"
                :options="['menunggu' => 'Menunggu', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak']"
                :selected="$permintaanUpgrade->status" />
        </div>
    </form>
    <x-slot:actions>
        <button form="form" type="submit"
                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl transition">
            Simpan Perubahan
        </button>
        <a href="{{ route('permintaan-upgrade.index') }}"
           class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm rounded-xl transition hover:border-slate-300">
            Batal
        </a>
    </x-slot:actions>
</x-ui.form-card>
@endsection