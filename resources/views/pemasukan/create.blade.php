@extends('layouts.app')
@section('title', 'Tambah Pemasukan')
@section('content')

<x-ui.form-card title="Tambah Data Pemasukan" :back-route="route('pemasukan.index')">

   <form method="POST" action="{{ route('pemasukan.store') }}" id="form" enctype="multipart/form-data"
    x-data="{
        idPelanggan: '',
        idTagihan: '',
        tagihans: [],
        loading: false,

        async fetchTagihan(idPelanggan) {
            console.log('Fungsi fetchTagihan terpanggil untuk ID:', idPelanggan); 
            
            this.idTagihan = '';
            this.tagihans = [];

            if (!idPelanggan) {
                console.log('ID Pelanggan kosong, fetch dibatalkan.');
                return;
            } /* <-- Tadi kamu lupa tutup kurung di sini */

            this.loading = true;
            try {
                const res = await fetch(`{{ route('pemasukan.tagihan-pelanggan') }}?id_pelanggan=${idPelanggan}`);
                this.tagihans = await res.json();
                console.log('Data diterima:', this.tagihans);
            } catch(e) {
                console.error('Fetch Error:', e);
            } finally {
                this.loading = false;
            }
        },

        /* Saat pilih tagihan → isi otomatis field lainnya */
        pilihTagihan(idTagihan) {
            if (!idTagihan) return;

            const tagihan = this.tagihans.find(t => t.id == idTagihan);
            if (!tagihan) return;

            /* Isi field otomatis menggunakan ID elemen */
            document.getElementById('jumlah_bayar').value = tagihan.jumlah;
            document.getElementById('bulan_tagihan').value = tagihan.bulan;
            document.getElementById('tahun_tagihan').value = tagihan.tahun;
            document.getElementById('jenis_pemasukan').value = 'Tagihan Bulanan';
        }
    }">
    @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- ── Pilih Pelanggan ─────────────────────── --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                    Pelanggan <span class="text-red-400">*</span>
                </label>
                <select
                    name="id_pelanggan"
                    x-model="idPelanggan"
                    @change="fetchTagihan(idPelanggan)"
                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm
                           focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                >
                    <option value="">Pilih Pelanggan...</option>
                    @foreach($pelanggans as $p)
                        <option value="{{ $p->id }}" @selected(old('id_pelanggan') == $p->id)>
                            {{ $p->nama }} — {{ $p->paket_langganan }}
                        </option>
                    @endforeach
                </select>
                @error('id_pelanggan')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── Pilih Tagihan (muncul setelah pilih pelanggan) ── --}}
            <div class="md:col-span-2" x-show="idPelanggan" x-transition>

                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                    Tagihan Terkait
                    <span class="text-slate-400 font-normal ml-1">(opsional)</span>
                </label>

                {{-- Loading --}}
                <div x-show="loading" class="flex items-center gap-2 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-400">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    Memuat tagihan...
                </div>

                {{-- Dropdown Tagihan --}}
                <div x-show="!loading">

                    {{-- Tidak ada tagihan belum lunas --}}
                    <div x-show="tagihans.length === 0 && idPelanggan"
                         class="flex items-center gap-2 px-4 py-3 bg-amber-50 border border-amber-100 rounded-xl text-sm text-amber-600">
                        @include('components.ui.icons', ['icon' => 'info', 'class' => 'w-4 h-4 flex-shrink-0'])
                        Pelanggan ini tidak memiliki tagihan yang belum lunas.
                    </div>

                    {{-- Ada tagihan --}}
                    <div x-show="tagihans.length > 0">
                        <select
                            name="id_tagihan"
                            x-model="idTagihan"
                            @change="pilihTagihan($event.target.value)"
                            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        >
                            <option value="">-- Pilih Periode Tagihan --</option>
                            <template x-for="t in tagihans" :key="t.id">
                                <option :value="t.id">
                                    <span x-text="`${t.bulan} ${t.tahun} — Rp ${Number(t.jumlah).toLocaleString('id-ID')} (${t.status})`"></span>
                                </option>
                            </template>
                        </select>

                        {{-- Info tagihan terpilih --}}
                        <div x-show="idTagihan"
                             class="mt-2 flex items-center gap-2 p-3 bg-blue-50 border border-blue-100 rounded-xl text-xs text-blue-600">
                            @include('components.ui.icons', ['icon' => 'info', 'class' => 'w-4 h-4 flex-shrink-0'])
                            Jumlah, bulan, dan tahun tagihan sudah terisi otomatis. Kamu bisa ubah jika diperlukan.
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Jenis Pemasukan ─────────────────────── --}}
            <x-ui.input
                name="jenis_pemasukan"
                id="jenis_pemasukan"
                label="Jenis Pemasukan"
                required
                placeholder="Contoh: Tagihan Bulanan, Pasang Baru"
                :value="old('jenis_pemasukan')"
            />

            {{-- ── Jumlah Bayar (auto-fill) ─────────────── --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                    Jumlah Bayar (Rp) <span class="text-red-400">*</span>
                </label>
                <input
                    type="number"
                    name="jumlah_bayar"
                    id="jumlah_bayar"
                    value="{{ old('jumlah_bayar') }}"
                    required
                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm
                           focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                >
                @error('jumlah_bayar')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── Metode Bayar ────────────────────────── --}}
            <x-ui.select
                name="metode_bayar"
                label="Metode Pembayaran"
                required
                :options="[
                    'Transfer Bank' => 'Transfer Bank',
                    'Tunai'         => 'Tunai',
                    'QRIS'          => 'QRIS',
                    'Lainnya'       => 'Lainnya',
                ]"
            />

            {{-- ── Status ──────────────────────────────── --}}
            <x-ui.select
                name="status"
                label="Status Pembayaran"
                required
                :options="[
                    'lunas'     => 'Lunas',
                    'menunggu'  => 'Menunggu Verifikasi',
                ]"
                selected="lunas"
            />

            {{-- ── Bulan Tagihan (auto-fill) ────────────── --}}
            <x-ui.select
                name="bulan_tagihan"
                id="bulan_tagihan"
                label="Bulan Tagihan"
                :options="[
                    'Januari'   => 'Januari',   'Februari'  => 'Februari',
                    'Maret'     => 'Maret',     'April'     => 'April',
                    'Mei'       => 'Mei',        'Juni'      => 'Juni',
                    'Juli'      => 'Juli',       'Agustus'   => 'Agustus',
                    'September' => 'September', 'Oktober'   => 'Oktober',
                    'November'  => 'November',  'Desember'  => 'Desember',
                ]"
            />

            {{-- ── Tahun Tagihan (auto-fill) ────────────── --}}
            <x-ui.input
                name="tahun_tagihan"
                id="tahun_tagihan"
                label="Tahun Tagihan"
                type="number"
                :value="old('tahun_tagihan', date('Y'))"
            />

            {{-- ── Tanggal Bayar ────────────────────────── --}}
            <x-ui.input
                name="tanggal_bayar"
                label="Tanggal Bayar"
                type="datetime-local"
                :value="old('tanggal_bayar', now()->format('Y-m-d\TH:i'))"
            />

            {{-- ── Bukti Bayar ─────────────────────────── --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                    Bukti Pembayaran
                </label>
                <input
                    type="file"
                    name="bukti_bayar"
                    accept="image/*,.pdf"
                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm
                           file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0
                           file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700
                           hover:file:bg-blue-100 focus:outline-none focus:ring-2
                           focus:ring-blue-500 transition"
                >
                <p class="mt-1.5 text-xs text-slate-400">Format: JPG, PNG, PDF. Maks 2MB</p>
            </div>

            {{-- ── Keterangan ──────────────────────────── --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Keterangan</label>
                <textarea
                    name="keterangan"
                    rows="2"
                    placeholder="Catatan tambahan (opsional)..."
                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm
                           focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                >{{ old('keterangan') }}</textarea>
            </div>

        </div>
    </form>

    <x-slot:actions>
        <button form="form" type="submit"
                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm
                       font-semibold rounded-xl transition">
            Simpan Pemasukan
        </button>
        <a href="{{ route('pemasukan.index') }}"
           class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm
                  rounded-xl transition hover:border-slate-300">
            Batal
        </a>
    </x-slot:actions>

</x-ui.form-card>

@endsection