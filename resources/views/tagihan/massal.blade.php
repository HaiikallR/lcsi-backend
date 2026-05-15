@extends('layouts.app')
@section('title', 'Tagihan Massal')
@section('content')

<x-ui.form-card title="Buat Tagihan Massal" :back-route="route('tagihan.index')">

    {{-- Info box --}}
    <div class="flex items-start gap-3 p-4 bg-blue-50 border border-blue-100 rounded-xl">
        @include('components.ui.icons', ['icon' => 'info', 'class' => 'w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5'])
        <div>
            <p class="text-sm font-medium text-blue-700">Informasi Tagihan Massal</p>
            <p class="text-xs text-blue-500 mt-1">
                Tagihan akan dibuat untuk <strong>{{ $totalPelanggan }} pelanggan aktif</strong>.
                Pelanggan yang sudah memiliki tagihan di periode yang sama akan otomatis di-skip.
            </p>
        </div>
    </div>

    <form method="POST" action="{{ route('tagihan.massal.store') }}" id="form">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <x-ui.input
                name="jumlah"
                label="Nominal Tagihan (Rp)"
                type="number"
                required
                help="Nominal yang sama akan diterapkan ke semua pelanggan"
            />

            <x-ui.select
                name="bulan"
                label="Bulan"
                required
                :options="[
                    'Januari'   => 'Januari',
                    'Februari'  => 'Februari',
                    'Maret'     => 'Maret',
                    'April'     => 'April',
                    'Mei'       => 'Mei',
                    'Juni'      => 'Juni',
                    'Juli'      => 'Juli',
                    'Agustus'   => 'Agustus',
                    'September' => 'September',
                    'Oktober'   => 'Oktober',
                    'November'  => 'November',
                    'Desember'  => 'Desember',
                ]"
            />

            <x-ui.input
                name="tahun"
                label="Tahun"
                type="number"
                :value="date('Y')"
                required
            />

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Catatan</label>
                <textarea name="catatan" rows="2"
                          class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm
                                 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                          placeholder="Catatan untuk semua tagihan (opsional)">{{ old('catatan') }}</textarea>
            </div>

        </div>
    </form>

    <x-slot:actions>
        <button form="form" type="submit"
                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm
                       font-semibold rounded-xl transition inline-flex items-center gap-2">
            @include('components.ui.icons', ['icon' => 'users', 'class' => 'w-4 h-4'])
            Buat Tagihan untuk Semua Pelanggan
        </button>
        <a href="{{ route('tagihan.index') }}"
           class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm rounded-xl transition hover:border-slate-300">
            Batal
        </a>
    </x-slot:actions>

</x-ui.form-card>

@endsection