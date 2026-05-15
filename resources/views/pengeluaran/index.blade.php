@extends('layouts.app')
@section('title', 'Manajemen Pengeluaran')
@section('content')

<x-ui.page-header
    title="Manajemen Pengeluaran"
    :sub="'Total ' . $pengeluarans->total() . ' data pengeluaran'"
    create-route="pengeluaran.create"
    create-label="Tambah Pengeluaran"
/>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50/70 border-b border-slate-100">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">No</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Judul</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Kategori Pengeluaran
                    </th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Bulan</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Tahun</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Jumlah</th>
                    <th class="px-5 py-3.5 text-right text-xs font-semibold text-slate-400 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($pengeluarans as $p)
                <tr class="hover:bg-slate-50/40 transition">
                    <td class="px-5 py-3.5 font-medium text-slate-800">{{ $loop->iteration }}</td>
                    <td class="px-5 py-3.5 font-medium text-slate-800">{{ $p->judul }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $p->kategori }}</td>
                    <td class="px-5 py-3.5 font-medium text-slate-700">{{ $p->bulan }}</td>
                    <td class="px-5 py-3.5 font-medium text-slate-700">{{ $p->tahun }}</td>
                    <td class="px-5 py-3.5 font-medium text-slate-700">Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('pengeluaran.edit', $p) }}"
                               class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition">
                                @include('Components.ui.icons', ['icon' => 'edit', 'class' => 'w-4 h-4'])
                            </a>
                            <form method="POST" action="{{ route('pengeluaran.destroy', $p) }}"
                                  x-data @submit.prevent="if(confirm('Hapus data pengeluaran ini?')) $el.submit()">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                    @include('Components.ui.icons', ['icon' => 'trash', 'class' => 'w-4 h-4'])
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-slate-300 text-sm">
                        Belum ada data pengeluaran
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pengeluarans->hasPages())
    <div class="px-5 py-4 border-t border-slate-50">{{ $pengeluarans->links() }}</div>
    @endif
</div>
@endsection