@extends('layouts.app')
@section('title', 'Manajemen Pelanggan')
@section('content')

<x-ui.page-header
    title="Manajemen Pelanggan"
    :sub="'Total ' . $pelanggans->total() . ' pelanggan terdaftar'"
    create-route="pelanggan.create"
    create-label="Tambah Pelanggan"
/>

{{-- Statistik Ringkas --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <x-ui.stat-card label="Total Pelanggan" :value="$total['semua']"    icon="users"  color="blue" />
    <x-ui.stat-card label="Aktif"           :value="$total['aktif']"    icon="check-circle" color="green" />
    <x-ui.stat-card label="Tidak Aktif"     :value="$total['nonaktif']" icon="x-circle"     color="red" />
    <x-ui.stat-card label="Paket Terdaftar" :value="$total['paket']"    icon="bar-chart"    color="purple" />
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

    {{-- Search & Filter --}}
    <div class="px-5 py-4 border-b border-slate-50 flex items-center gap-3">
        <form method="GET" action="{{ route('pelanggan.index') }}" class="flex items-center gap-3 flex-1">
            <div class="relative flex-1 max-w-xs">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                    @include('components.ui.icons', ['icon' => 'search', 'class' => 'w-4 h-4'])
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama atau email..."
                       class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-xl text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>
            <select name="status"
                    class="px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="aktif"       @selected(request('status') == 'aktif')>Aktif</option>
                <option value="tidak aktif" @selected(request('status') == 'tidak aktif')>Tidak Aktif</option>
            </select>
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded-xl hover:bg-blue-500 transition">
                Cari
            </button>
            @if(request('search') || request('status'))
            <a href="{{ route('pelanggan.index') }}"
               class="px-4 py-2 bg-slate-100 text-slate-600 text-sm rounded-xl hover:bg-slate-200 transition">
                Reset
            </a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50/70 border-b border-slate-100">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Pelanggan</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">No. HP</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Paket</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Status</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Terdaftar</th>
                    <th class="px-5 py-3.5 text-right text-xs font-semibold text-slate-400 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($pelanggans as $p)
                <tr class="hover:bg-slate-50/40 transition">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <span class="text-blue-600 text-sm font-bold">
                                    {{ strtoupper(substr($p->nama, 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <p class="font-medium text-slate-800">{{ $p->nama }}</p>
                                <p class="text-xs text-slate-400">{{ $p->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $p->no_hp }}</td>
                    <td class="px-5 py-3.5">
                        <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-lg">
                            {{ $p->paket_langganan }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5"><x-ui.badge :status="$p->status" /></td>
                    <td class="px-5 py-3.5 text-slate-400 text-xs">{{ $p->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('pelanggan.show', $p) }}"
                               class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition"
                               title="Detail">
                                @include('components.ui.icons', ['icon' => 'eye', 'class' => 'w-4 h-4'])
                            </a>
                            <a href="{{ route('pelanggan.edit', $p) }}"
                               class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition"
                               title="Edit">
                                @include('components.ui.icons', ['icon' => 'edit', 'class' => 'w-4 h-4'])
                            </a>
                            <form method="POST" action="{{ route('pelanggan.destroy', $p) }}"
                                  x-data @submit.prevent="if(confirm('Hapus pelanggan {{ $p->nama }}?')) $el.submit()">
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
                    <td colspan="6" class="px-5 py-12 text-center text-slate-300 text-sm">
                        @if(request('search') || request('status'))
                            Tidak ada hasil untuk pencarian ini
                        @else
                            Belum ada data pelanggan
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pelanggans->hasPages())
    <div class="px-5 py-4 border-t border-slate-50">
        {{ $pelanggans->links() }}
    </div>
    @endif
</div>

@endsection