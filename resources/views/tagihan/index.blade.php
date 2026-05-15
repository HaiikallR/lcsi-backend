@extends('layouts.app')
@section('title', 'Manajemen Tagihan')
@section('content')

<x-ui.page-header 
        title="Manajemen Tagihan" 
        :sub="'Total ' . $tagihans->total() . ' tagihan'" 
        create-route="tagihan.create" 
        create-label="Buat Tagihan Khusus" 
    />
    
    <div class="mb-4 flex items-center justify-end gap-2  border-blue-500 pb-4">
    <a href="{{ route('tagihan.massal') }}" 
       class="inline-flex items-center px-3 py-3 bg-blue-600 border rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
        <i class="fas fa-users mr-4"></i> Buat Tagihan Massal
    </a>
</div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50/70 border-b border-slate-100">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Pelanggan</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Periode</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Jumlah</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Status</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase">Tgl Bayar</th>
                    <th class="px-5 py-3.5 text-right text-xs font-semibold text-slate-400 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($tagihans as $t)
                <tr class="hover:bg-slate-50/40 transition">
                    <td class="px-5 py-3.5 font-medium text-slate-800">{{ $t->pelanggan?->nama ?? '-' }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $t->bulan }} {{ $t->tahun }}</td>
                    <td class="px-5 py-3.5 font-medium text-slate-700">Rp {{ number_format($t->jumlah, 0, ',', '.') }}</td>
                    <td class="px-5 py-3.5"><x-ui.badge :status="$t->status" /></td>
                    <td class="px-5 py-3.5 text-slate-400 text-xs">{{ $t->tanggal_bayar?->format('d M Y') ?? '-' }}</td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('tagihan.edit', $t) }}" class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition">
                                @include('Components.ui.icons', ['icon' => 'edit', 'class' => 'w-4 h-4'])
                            </a>
                            <form method="POST" action="{{ route('tagihan.destroy', $t) }}" x-data @submit.prevent="if(confirm('Hapus tagihan ini?')) $el.submit()">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                    @include('Components.ui.icons', ['icon' => 'trash', 'class' => 'w-4 h-4'])
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-12 text-center text-slate-300 text-sm">Belum ada tagihan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tagihans->hasPages())
    <div class="px-5 py-4 border-t border-slate-50">{{ $tagihans->links() }}</div>
    @endif
</div>
@endsection