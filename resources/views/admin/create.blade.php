@extends('layouts.app')
@section('title', 'Tambah Admin')
@section('content')
<x-ui.form-card title="Tambah Admin Baru" :back-route="route('admin.index')">
    <form method="POST" action="{{ route('admin.store') }}" id="formAdmin">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <x-ui.input name="nama"     label="Nama Lengkap" required />
            <x-ui.input name="email"    label="Email" type="email" required />
            <x-ui.input name="password" label="Password" type="password" required help="Minimal 8 karakter" />
        </div>
    </form>
    <x-slot:actions>
        <button form="formAdmin" type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl transition">Simpan Admin</button>
        <a href="{{ route('admin.index') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm rounded-xl transition hover:border-slate-300">Batal</a>
    </x-slot:actions>
</x-ui.form-card>
@endsection