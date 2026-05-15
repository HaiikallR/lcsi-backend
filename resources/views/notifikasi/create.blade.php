@extends('layouts.app')
@section('title', 'Kirim Notifikasi')
@section('content')

<x-ui.form-card title="Kirim Notifikasi ke Pelanggan" :back-route="route('notifikasi.index')">

    {{-- Info FCM --}}
    <div class="flex items-start gap-3 p-4 bg-blue-50 border border-blue-100 rounded-xl">
        @include('components.ui.icons', ['icon' => 'info', 'class' => 'w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5'])
        <div>
            <p class="text-sm font-medium text-blue-700">Push Notification via FCM</p>
            <p class="text-xs text-blue-500 mt-0.5">
                Notifikasi dikirim langsung ke HP pelanggan melalui Firebase Cloud Messaging.
                Pastikan pelanggan sudah login di aplikasi mobile agar memiliki device token.
            </p>
        </div>
    </div>

    <form method="POST" action="{{ route('notifikasi.store') }}" id="form">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- Pilih Pelanggan --}}
            <div class="md:col-span-2" x-data="{ selected: null, pelanggans: @js($pelanggans) }">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                    Pelanggan <span class="text-red-400">*</span>
                </label>
                <select name="id_pelanggan" required
                        x-model="selected"
                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <option value="">Pilih Pelanggan...</option>
                    @foreach($pelanggans as $p)
                        <option value="{{ $p->id }}" @selected(old('id_pelanggan') == $p->id)>
                            {{ $p->nama }}
                            @if(!$p->device_token) ⚠️ (belum ada device token) @endif
                        </option>
                    @endforeach
                </select>

                {{-- Warning jika pelanggan tidak punya device token --}}
                <template x-if="selected">
                    <div x-show="pelanggans.find(p => p.id == selected)?.device_token === null"
                         class="mt-2 flex items-center gap-2 p-3 bg-amber-50 border border-amber-100 rounded-xl">
                        @include('components.ui.icons', ['icon' => 'alert-triangle', 'class' => 'w-4 h-4 text-amber-500'])
                        <p class="text-xs text-amber-600">
                            Pelanggan ini belum memiliki device token.
                            Notifikasi akan tersimpan di database tapi tidak akan terkirim ke HP.
                        </p>
                    </div>
                </template>

                @error('id_pelanggan')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Judul --}}
            <x-ui.input
                name="judul"
                label="Judul Notifikasi"
                required
                placeholder="Contoh: Tagihan Bulan Ini Sudah Tersedia"
                :value="old('judul')"
                help="Maksimal 100 karakter"
            />

            {{-- Kategori --}}
            <x-ui.select
                name="kategori"
                label="Kategori"
                required
                :options="[
                    'info'    => '🔵 Info',
                    'success' => '🟢 Success',
                    'warning' => '🟡 Warning',
                    'error'   => '🔴 Error',
                ]"
                :selected="old('kategori', 'info')"
            />

            {{-- Pesan --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                    Isi Pesan <span class="text-red-400">*</span>
                </label>
                <textarea
                    name="pesan"
                    rows="4"
                    required
                    placeholder="Tulis isi pesan notifikasi yang akan diterima pelanggan..."
                    class="w-full px-4 py-2.5 bg-white border rounded-xl text-sm text-slate-800
                           placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500
                           transition resize-none {{ $errors->has('pesan') ? 'border-red-300' : 'border-slate-200' }}"
                >{{ old('pesan') }}</textarea>
                @error('pesan')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

        </div>
    </form>

    <x-slot:actions>
        <button form="form" type="submit"
                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm
                       font-semibold rounded-xl transition inline-flex items-center gap-2">
            @include('components.ui.icons', ['icon' => 'bell', 'class' => 'w-4 h-4'])
            Kirim Notifikasi
        </button>
        <a href="{{ route('notifikasi.index') }}"
           class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600
                  text-sm rounded-xl transition hover:border-slate-300">
            Batal
        </a>
    </x-slot:actions>

</x-ui.form-card>

@endsection