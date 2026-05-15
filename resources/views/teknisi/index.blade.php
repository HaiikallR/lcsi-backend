@extends('layouts.app')
@section('title', 'Manajemen Teknisi')
@section('content')

<x-ui.page-header title="Manajemen Teknisi" :sub="'Total ' . $teknisis->total() . ' teknisi'" create-route="teknisi.create" create-label="Tambah Teknisi" />

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50/70 border-b border-slate-100">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Nama</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">No. HP</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3.5 text-right text-xs font-semibold text-slate-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($teknisis as $t)
                <tr class="hover:bg-slate-50/40 transition">
                    <td class="px-5 py-3.5 font-medium text-slate-800">{{ $t->nama }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $t->no_hp }}</td>
                    <td class="px-5 py-3.5"><x-ui.badge :status="$t->status" /></td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('teknisi.edit', $t) }}" class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition">
                                @include('Components.ui.icons', ['icon' => 'edit', 'class' => 'w-4 h-4'])
                            </a>
                            <form method="POST" action="{{ route('teknisi.destroy', $t) }}" x-data @submit.prevent="if(confirm('Hapus teknisi {{ $t->nama }}?')) $el.submit()">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                    @include('Components.ui.icons', ['icon' => 'trash', 'class' => 'w-4 h-4'])
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-5 py-12 text-center text-slate-300 text-sm">Belum ada teknisi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($teknisis->hasPages())
    <div class="px-5 py-4 border-t border-slate-50">{{ $teknisis->links() }}</div>
    @endif
</div>
@endsection