@extends('layouts.app')
@section('title', 'Manajemen Admin')
@section('content')

<x-ui.page-header title="Manajemen Admin" :sub="'Total ' . $admins->total() . ' admin'" create-route="admin.create" create-label="Tambah Admin" />

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50/70 border-b border-slate-100">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Nama</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Email</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Terdaftar</th>
                    <th class="px-5 py-3.5 text-right text-xs font-semibold text-slate-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($admins as $a)
                <tr class="hover:bg-slate-50/40 transition">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="text-blue-600 text-xs font-bold">{{ strtoupper(substr($a->nama, 0, 1)) }}</span>
                            </div>
                            <span class="font-medium text-slate-800">{{ $a->nama }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $a->email }}</td>
                    <td class="px-5 py-3.5 text-slate-400">{{ $a->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.edit', $a) }}" class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition">
                                @include('Components.ui.icons', ['icon' => 'edit', 'class' => 'w-4 h-4'])
                            </a>
                            {{-- @if(Auth::guard('admin')->id() !== $a->id) --}}
                            <form method="POST" action="{{ route('admin.destroy', $a) }}" x-data @submit.prevent="if(confirm('Hapus admin {{ $a->nama }}?')) $el.submit()">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                    @include('Components.ui.icons', ['icon' => 'trash', 'class' => 'w-4 h-4'])
                                </button>
                            </form>
                            {{-- @endif --}}
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-5 py-12 text-center text-slate-300 text-sm">Belum ada admin</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($admins->hasPages())
    <div class="px-5 py-4 border-t border-slate-50">{{ $admins->links() }}</div>
    @endif
</div>

@endsection