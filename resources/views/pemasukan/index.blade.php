@extends('layouts.app')
@section('title', 'Manajemen Pemasukan')
@section('content')
<x-ui.page-header title="Manajemen Pemasukan" :sub="'Total ' . $pemasukans->total() . ' data'" create-route="pemasukan.create" create-label="Tambah Pemasukan" />
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-slate-50/70 border-b border-slate-100">
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Pelanggan</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Jenis</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Jumlah</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Metode</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Periode</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Bukti Pembayaran</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Status</th>
                <th class="px-5 py-3.5 text-right text-xs font-semibold text-slate-400 uppercase">Aksi</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($pemasukans as $p)
                <tr class="hover:bg-slate-50/40 transition">
                    <td class="px-5 py-3.5 font-medium text-slate-800">{{ $p->pelanggan?->nama ?? '-' }}</td>
                    <td class="px-5 py-3.5 text-slate-600">{{ $p->jenis_pemasukan }}</td>
                    <td class="px-5 py-3.5 font-medium text-emerald-700">Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $p->metode_bayar }}</td>
                    <td class="px-5 py-3.5 text-slate-400 text-xs">{{ $p->bulan_tagihan }} {{ $p->tahun_tagihan }}</td>
                  <td class="px-5 py-3.5">
         @if($p->bukti_bayar)
        <div class="flex flex-col gap-1">
            <a href="{{ asset('storage/' . $p->bukti_bayar) }}" target="_blank" class="group relative">
                <img src="{{ asset('storage/' . $p->bukti_bayar) }}" 
                     alt="Bukti" 
                     class="w-12 h-12 object-cover rounded-lg border border-slate-200 group-hover:opacity-75 transition">
                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                    <span class="text-[10px] bg-black/50 text-white px-1 rounded">Lihat</span>
                </div>
            </a>
        </div>
    @else
        <span class="text-slate-400 text-xs italic">Telah Diverifikasi Admin</span>
    @endif
</td>
                    <td class="px-5 py-3.5"><x-ui.badge :status="$p->status" /></td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('pemasukan.edit', $p) }}" class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition">
                                @include('Components.ui.icons', ['icon' => 'edit', 'class' => 'w-4 h-4'])
                            </a>
                            <form method="POST" action="{{ route('pemasukan.destroy', $p) }}" x-data @submit.prevent="if(confirm('Hapus data ini?')) $el.submit()">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                    @include('Components.ui.icons', ['icon' => 'trash', 'class' => 'w-4 h-4'])
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-12 text-center text-slate-300 text-sm">Belum ada data pemasukan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pemasukans->hasPages())<div class="px-5 py-4 border-t border-slate-50">{{ $pemasukans->links() }}</div>@endif
</div>
@endsection