@extends('layouts.app')
@section('title', 'Manajemen Perangkat')
@section('content')

<x-ui.page-header
    title="Manajemen Perangkat"
    :sub="'Total ' . $perangkats->total() . ' perangkat terdaftar'"
    create-route="perangkat.create"
    create-label="Tambah Perangkat"
/>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50/70 border-b border-slate-100">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Nama Perangkat</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Merk</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Serial Number</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Pelanggan</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Status</th>
                    <th class="px-5 py-3.5 text-right text-xs font-semibold text-slate-400 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($perangkats as $p)
                <tr class="hover:bg-slate-50/40 transition">
                    <td class="px-5 py-3.5 font-medium text-slate-800">{{ $p->nama_perangkat }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $p->merk }}</td>
                    <td class="px-5 py-3.5 text-slate-400 font-mono text-xs">{{ $p->serial_number }}</td>
                    <td class="px-5 py-3.5 text-slate-600">{{ $p->pelanggan?->nama ?? '-' }}</td>
                    <td class="px-5 py-3.5"><x-ui.badge :status="$p->status" /></td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('perangkat.edit', $p) }}"
                               class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition">
                                @include('components.ui.icons', ['icon' => 'edit', 'class' => 'w-4 h-4'])
                            </a>
                            <form method="POST" action="{{ route('perangkat.destroy', $p) }}"
                                  x-data @submit.prevent="if(confirm('Hapus perangkat ini?')) $el.submit()">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                    @include('components.ui.icons', ['icon' => 'trash', 'class' => 'w-4 h-4'])
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-slate-300 text-sm">
                        Belum ada data perangkat
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($perangkats->hasPages())
    <div class="px-5 py-4 border-t border-slate-50">{{ $perangkats->links() }}</div>
    @endif
</div>
@endsection