@extends('layouts.app')
@section('title', 'Verifikasi Pembayaran')
@section('content')

<x-ui.page-header
    title="Verifikasi Pembayaran"
    :sub="'Total ' . $verifikasis->total() . ' pengajuan'"
/>

{{-- Statistik --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <x-ui.stat-card label="Menunggu Verifikasi" :value="$statistik['menunggu']"  icon="file-text" color="yellow" />
    <x-ui.stat-card label="Lunas"           :value="$statistik['lunas']" icon="check-circle" color="green" />
    <x-ui.stat-card label="Ditolak"         :value="$statistik['ditolak']" icon="x-circle" color="red" />

</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50/70 border-b border-slate-100">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Pelanggan</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Periode</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Nominal</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Bukti</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Status</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Dikirim</th>
                    <th class="px-5 py-3.5 text-right text-xs font-semibold text-slate-400 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($verifikasis as $v)
                <tr class="hover:bg-slate-50/40 transition">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="text-blue-600 text-xs font-bold">
                                    {{ strtoupper(substr($v->pelanggan?->nama ?? '?', 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <p class="font-medium text-slate-800">{{ $v->pelanggan?->nama ?? '-' }}</p>
                                <p class="text-xs text-slate-400">{{ $v->pelanggan?->jumlah_bayar }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $v->bulan_tagihan }} {{ $v->tahun_tagihan}}</td>
                    <td class="px-5 py-3.5 font-medium text-slate-700">
                        Rp {{ number_format($v->jumlah_bayar ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-3.5">
                        {{-- Preview thumbnail bukti --}}
                        @if($v->bukti_bayar)
                            <a href="{{ Storage::url($v->bukti_bayar) }}" target="_blank"
                               class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 text-blue-600
                                      text-xs font-medium rounded-lg hover:bg-blue-100 transition">
                                @include('Components.ui.icons', ['icon' => 'eye', 'class' => 'w-3.5 h-3.5'])
                                Lihat Bukti
                            </a>
                        @else
                            <span class="text-slate-300 text-xs">Tidak ada</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5"><x-ui.badge :status="$v->status" /></td>
                    <td class="px-5 py-3.5 text-slate-400 text-xs">
                        {{ $v->created_at->format('d M Y H:i') }}
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-2">
                            {{-- Tombol Detail --}}
                            <a href="{{ route('verifikasi-pembayaran.show', $v) }}"
                               class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition"
                               title="Detail">
                                @include('components.ui.icons', ['icon' => 'eye', 'class' => 'w-4 h-4'])
                            </a>

                            {{-- Tombol Setujui (hanya jika masih menunggu) --}}
                            @if($v->status === 'menunggu')
                            <form method="POST" action="{{ route('verifikasi-pembayaran.setujui', $v) }}"
                                  x-data @submit.prevent="if(confirm('Setujui pembayaran ini?')) $el.submit()">
                                @csrf
                                <button type="submit"
                                        class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition"
                                        title="Setujui">
                                    @include('components.ui.icons', ['icon' => 'check-circle', 'class' => 'w-4 h-4'])
                                </button>
                            </form>
                            @endif

                            {{-- Tombol Hapus --}}
                            <form method="POST" action="{{ route('verifikasi-pembayaran.destroy', $v) }}"
                                  x-data @submit.prevent="if(confirm('Hapus data verifikasi ini?')) $el.submit()">
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
                        Belum ada pengajuan verifikasi pembayaran
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($verifikasis->hasPages())
    <div class="px-5 py-4 border-t border-slate-50">{{ $verifikasis->links() }}</div>
    @endif
</div>

@endsection