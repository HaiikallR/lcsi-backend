@extends('layouts.app')
@section('title', 'Tambah Pengeluaran')
@section('content')
<x-ui.form-card title="Tambah Data Pengeluaran" :back-route="route('pengeluaran.index')">
   <form method="POST" action="{{ route('pengeluaran.store') }}" id="form" enctype="multipart/form-data"
    x-data="{
        kategori: '',
        tikets: [],
        loading: false,

        async fetchTiket() {
            if (this.kategori !== 'Tiket') {
                this.tikets = [];
                return;
            }

            this.loading = true;
            try {
                // Pastikan route ini sudah kamu buat di web.php
                const res = await fetch(`{{ route('pengeluaran.tiket-terbuka') }}`);
                this.tikets = await res.json();
            } catch(e) {
                console.error('Gagal mengambil data tiket:', e);
            } finally {
                this.loading = false;
            }
        }
    }">
    @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

           <div class="md:col-span-2">
    <label class="block text-sm font-medium text-slate-700 mb-1.5">
        Kategori Pengeluaran <span class="text-red-400">*</span>
    </label>
    <select name="kategori"
            x-model="kategori"
            @change="fetchTiket()"
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Pilih Kategori Pengeluaran...</option>
        <option value="Tiket">Tiket Pekerjaan (Teknisi)</option>
        <option value="Alat">Pembelian Alat / Material</option>
        <option value="Operasional">Biaya Operasional</option>
    </select>
</div>
<div class="md:col-span-2" x-show="kategori === 'Tiket'" x-transition>
    <label class="block text-sm font-medium text-slate-700 mb-1.5">
        Pilih Tiket Terkait <span class="text-red-400">*</span>
        <span x-show="loading" class="ml-2 text-xs text-blue-500 animate-pulse italic">Mengambil data...</span>
    </label>
    <select name="id_tiket"
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">-- Pilih Tiket yang Belum Selesai --</option>
       <template x-for="tiket in tikets" :key="tiket.id">
    <option :value="tiket.id" 
            x-text="tiket.jenis_pekerjaan + ' (' + (tiket.pelanggan ? tiket.pelanggan.nama : tiket.calon_pelanggan_nama) + ')'">
    </option>
</template>
    </select>
    <p class="mt-1 text-xs text-slate-400">Pilih tiket untuk menghubungkan pengeluaran ini dengan pekerjaan teknisi.</p>
</div>

           

            <x-ui.input name="judul"    label="Judul Pengeluaran" required
                        help="Contoh: Biaya Kabel, Ongkir Material" />

            <x-ui.input name="jumlah"   label="Jumlah (Rp)" type="number" required />

            <x-ui.input name="bulan"    label="Bulan" required help="Contoh: Mei" />
            <x-ui.input name="tahun"    label="Tahun" type="number" :value="date('Y')" required />

        </div>
    </form>
    <x-slot:actions>
        <button form="form" type="submit"
                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl transition">
            Simpan Pengeluaran
        </button>
        <a href="{{ route('pengeluaran.index') }}"
           class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm rounded-xl transition hover:border-slate-300">
            Batal
        </a>
    </x-slot:actions>
</x-ui.form-card>
@endsection