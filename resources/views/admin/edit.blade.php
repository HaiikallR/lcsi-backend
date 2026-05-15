@extends('layouts.app')
@section('title', 'Edit Admin')
@section('content')
<x-ui.form-card title="Edit Admin" :back-route="route('admin.index')">
    <form method="POST" action="{{ route('admin.update', $admin) }}" id="formEdit">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <x-ui.input name="nama"     label="Nama Lengkap" :value="$admin->nama" required />
            <x-ui.input name="email"    label="Email" type="email" :value="$admin->email" required />
            <x-ui.input name="password" label="Password Baru" type="password" help="Kosongkan jika tidak ingin mengubah" />
        </div>
    </form>
    <x-slot:actions>
        <button form="formEdit" type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl transition">Simpan Perubahan</button>
        <a href="{{ route('admin.index') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm rounded-xl transition hover:border-slate-300">Batal</a>
    </x-slot:actions>
</x-ui.form-card>
@endsection