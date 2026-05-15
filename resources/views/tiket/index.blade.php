@extends('layouts.app')
@section('title', 'Manajemen Tiket')
@section('content')

<x-ui.page-header title="Manajemen Tiket" :sub="'Total ' . $tikets->total() . ' tiket'" create-route="tiket.create" create-label="Buat Tiket" />

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50/70 border-b border-slate-100">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">#</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Pelanggan/Calon</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Jenis Pekerjaan</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Teknisi</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Status</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Tgl Dibuat</th>
                    <th class="px-5 py-3.5 text-right text-xs font-semibold text-slate-400 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($tikets as $t)
                <tr class="hover:bg-slate-50/40 transition">
                    <td class="px-5 py-3.5 text-slate-400 font-mono text-xs">#{{ $t->id }}</td>
                    <td class="px-5 py-3.5 font-medium text-slate-800">
                        {{ $t->pelanggan?->nama ?? $t->calon_pelanggan_nama ?? '-' }}
                        @if(!$t->pelanggan)
                            <span class="ml-1 text-xs text-amber-500 font-normal">(Calon Pelanggan)</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-slate-600">{{ $t->jenis_pekerjaan }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $t->teknisi?->nama ?? '-' }}</td>
                    <td class="px-5 py-3.5"><x-ui.badge :status="$t->status" /></td>
                    <td class="px-5 py-3.5 text-slate-400 text-xs">{{ $t->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('tiket.show', $t) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                @include('Components.ui.icons', ['icon' => 'eye', 'class' => 'w-4 h-4'])
                            </a>
                             {{-- ✅ Tombol Kirim WhatsApp --}}
                            @if($t->teknisi?->no_hp)
                            <a href="{{ route('tiket.whatsapp', $t) }}"
                               target="_blank"
                               class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition"
                               title="Kirim ke WhatsApp Teknisi">
                                {{-- Icon WhatsApp --}}
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                            </a>
                            @else
                            <span class="p-1.5 text-slate-200 cursor-not-allowed" title="Teknisi tidak punya nomor HP">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                            </span>
                            @endif
                            <a href="{{ route('tiket.edit', $t) }}" class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition">
                                @include('Components.ui.icons', ['icon' => 'edit', 'class' => 'w-4 h-4'])
                            </a>
                            <form method="POST" action="{{ route('tiket.destroy', $t) }}" x-data @submit.prevent="if(confirm('Hapus tiket #{{ $t->id }}?')) $el.submit()">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                    @include('Components.ui.icons', ['icon' => 'trash', 'class' => 'w-4 h-4'])
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-12 text-center text-slate-300 text-sm">Belum ada tiket</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tikets->hasPages())
    <div class="px-5 py-4 border-t border-slate-50">{{ $tikets->links() }}</div>
    @endif
</div>
@endsection