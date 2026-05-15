@extends('layouts.app')
@section('title', 'Permintaan Upgrade')
@section('content')

<x-ui.page-header
    title="Permintaan Upgrade Paket"
    :sub="'Total ' . $upgrades->total() . ' permintaan'"
    create-route="permintaan-upgrade.create"
    create-label="Tambah Permintaan"
/>

{{-- Statistik --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <x-ui.stat-card
        label="Menunggu"
        :value="$statistik['menunggu']"
        icon="file-text"
        color="yellow"
    />
    <x-ui.stat-card
        label="Disetujui"
        :value="$statistik['disetujui']"
        icon="check-circle"
        color="green"
    />
    <x-ui.stat-card
        label="Ditolak"
        :value="$statistik['ditolak']"
        icon="x-circle"
        color="red"
    />
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50/70 border-b border-slate-100">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Pelanggan</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Paket Lama</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Paket Baru</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Harga Baru</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Status</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Tanggal</th>
                    <th class="px-5 py-3.5 text-right text-xs font-semibold text-slate-400 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($upgrades as $u)
                <tr class="hover:bg-slate-50/40 transition">
                    <td class="px-5 py-3.5">
                        <p class="font-medium text-slate-800">{{ $u->pelanggan?->nama ?? '-' }}</p>
                        <p class="text-xs text-slate-400">{{ $u->pelanggan?->email }}</p>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-xs font-medium rounded-lg">
                            {{ $u->paket_lama }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-lg">
                            {{ $u->paket_baru }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 font-medium text-slate-700">
                        Rp {{ number_format($u->harga_baru, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-3.5"><x-ui.badge :status="$u->status" /></td>
                    <td class="px-5 py-3.5 text-slate-400 text-xs">
                        {{ $u->created_at->format('d M Y') }}
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-1.5">

                            {{-- Detail --}}
                            <a href="{{ route('permintaan-upgrade.show', $u) }}"
                               class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition"
                               title="Detail">
                                @include('components.ui.icons', ['icon' => 'eye', 'class' => 'w-4 h-4'])
                            </a>

                            {{-- Tombol Setujui & Tolak hanya jika masih menunggu --}}
                            @if($u->status === 'menunggu')
                                {{-- Setujui --}}
                                <form method="POST" action="{{ route('permintaan-upgrade.setujui', $u) }}"
                                      x-data @submit.prevent="if(confirm('Setujui upgrade paket {{ $u->pelanggan?->nama }}?')) $el.submit()">
                                    @csrf
                                    <button type="submit"
                                            class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition"
                                            title="Setujui">
                                        @include('components.ui.icons', ['icon' => 'check-circle', 'class' => 'w-4 h-4'])
                                    </button>
                                </form>

                                {{-- Tolak → buka modal --}}
                                <button
                                    @click="$dispatch('open-tolak', { id: {{ $u->id }}, nama: '{{ $u->pelanggan?->nama }}' })"
                                    class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                    title="Tolak">
                                    @include('components.ui.icons', ['icon' => 'x-circle', 'class' => 'w-4 h-4'])
                                </button>
                            @endif

                            {{-- Hapus --}}
                            <form method="POST" action="{{ route('permintaan-upgrade.destroy', $u) }}"
                                  x-data @submit.prevent="if(confirm('Hapus permintaan ini?')) $el.submit()">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                        title="Hapus">
                                    @include('components.ui.icons', ['icon' => 'trash', 'class' => 'w-4 h-4'])
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-slate-300 text-sm">
                        Belum ada permintaan upgrade
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($upgrades->hasPages())
    <div class="px-5 py-4 border-t border-slate-50">{{ $upgrades->links() }}</div>
    @endif
</div>

{{-- ✅ Modal Tolak --}}
<div
    x-data="{
        open: false,
        id: null,
        nama: '',
        init() {
            window.addEventListener('open-tolak', (e) => {
                this.id   = e.detail.id
                this.nama = e.detail.nama
                this.open = true
            })
        }
    }"
    x-show="open"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="display: none"
>
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/40" @click="open = false"></div>

    {{-- Modal Box --}}
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">

        {{-- Header --}}
        <div class="flex items-start justify-between mb-5">
            <div>
                <h3 class="font-bold text-slate-800" style="font-family: 'Sora', sans-serif;">
                    Tolak Permintaan Upgrade
                </h3>
                <p class="text-sm text-slate-400 mt-1">
                    Permintaan dari <span class="font-medium text-slate-600" x-text="nama"></span>
                </p>
            </div>
            <button @click="open = false"
                    class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition">
                @include('components.ui.icons', ['icon' => 'x', 'class' => 'w-4 h-4'])
            </button>
        </div>

        {{-- Info --}}
        <div class="flex items-start gap-3 p-3 bg-red-50 border border-red-100 rounded-xl mb-5">
            @include('components.ui.icons', ['icon' => 'alert-triangle', 'class' => 'w-5 h-5 text-red-500 flex-shrink-0 mt-0.5'])
            <p class="text-sm text-red-600">
                Pelanggan akan mendapatkan notifikasi bahwa permintaan upgrade nya ditolak beserta alasannya.
            </p>
        </div>

        {{-- Form Tolak --}}
        <form
            method="POST"
            :action="`/permintaan-upgrade/${id}/tolak`"
        >
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                    Alasan Penolakan <span class="text-red-400">*</span>
                </label>
                <textarea
                    name="alasan_tolak"
                    rows="4"
                    required
                    minlength="5"
                    placeholder="Jelaskan alasan penolakan permintaan upgrade ini..."
                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm
                           text-slate-800 placeholder-slate-300 focus:outline-none
                           focus:ring-2 focus:ring-red-500 transition resize-none"
                ></textarea>
                @error('alasan_tolak')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3 mt-5">
                <button type="submit"
                        class="flex-1 py-2.5 bg-red-600 hover:bg-red-500 text-white text-sm
                               font-semibold rounded-xl transition">
                    Konfirmasi Penolakan
                </button>
                <button type="button" @click="open = false"
                        class="flex-1 py-2.5 bg-white border border-slate-200 text-slate-600
                               text-sm font-medium rounded-xl hover:border-slate-300 transition">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

@endsection