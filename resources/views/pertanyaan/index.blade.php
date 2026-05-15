@extends('layouts.app')
@section('title', 'FAQ & Pertanyaan')
@section('content')

<x-ui.page-header
    title="FAQ & Pertanyaan"
    :sub="'Total ' . $pertanyaans->total() . ' pertanyaan'"
    create-route="pertanyaan.create"
    create-label="Tambah FAQ"
/>

{{-- Filter Kategori --}}
@if($kategoris->count() > 0)
<div class="flex items-center gap-2 mb-5 flex-wrap">
    <a href="{{ route('pertanyaan.index') }}"
       class="px-3 py-1.5 rounded-lg text-sm font-medium transition
              {{ !request('kategori')
                  ? 'bg-blue-600 text-white'
                  : 'bg-white border border-slate-200 text-slate-600 hover:border-slate-300' }}">
        Semua
    </a>
    @foreach($kategoris as $kat)
    <a href="{{ route('pertanyaan.index', ['kategori' => $kat]) }}"
       class="px-3 py-1.5 rounded-lg text-sm font-medium transition
              {{ request('kategori') == $kat
                  ? 'bg-blue-600 text-white'
                  : 'bg-white border border-slate-200 text-slate-600 hover:border-slate-300' }}">
        {{ $kat }}
    </a>
    @endforeach
</div>
@endif

{{-- Daftar FAQ --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

    @forelse($pertanyaans as $p)
    <div class="border-b border-slate-50 last:border-0" x-data="{ open: false }">
        <div class="px-6 py-4">
            <div class="flex items-start justify-between gap-4">

                {{-- Pertanyaan + Jawaban (toggle) --}}
                <button @click="open = !open" class="flex-1 text-left">
                    <div class="flex items-start gap-3">
                        {{-- Nomor urut --}}
                        <span class="flex-shrink-0 w-6 h-6 bg-blue-50 text-blue-600 text-xs font-bold
                                     rounded-lg flex items-center justify-center mt-0.5">
                            {{ $loop->iteration }}
                        </span>

                        <div class="flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                {{-- Kategori badge --}}
                                @if($p->kategori)
                                <span class="px-2 py-0.5 bg-blue-50 text-blue-600 text-xs font-medium rounded-md">
                                    {{ $p->kategori }}
                                </span>
                                @endif

                                <p class="font-medium text-slate-800">{{ $p->pertanyaan }}</p>
                            </div>

                            {{-- Jawaban toggle --}}
                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="mt-3 text-sm text-slate-500 leading-relaxed
                                        border-l-2 border-blue-200 pl-3">
                                {!! nl2br(e($p->jawaban)) !!}
                            </div>
                        </div>

                        {{-- Icon toggle --}}
                        <span class="flex-shrink-0 text-slate-300 transition-transform duration-200 mt-0.5"
                              :class="open ? 'rotate-180' : ''">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </span>
                    </div>
                </button>

                {{-- Aksi --}}
                <div class="flex items-center gap-1 flex-shrink-0">
                    <a href="{{ route('pertanyaan.edit', $p) }}"
                       class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition"
                       title="Edit">
                        @include('components.ui.icons', ['icon' => 'edit', 'class' => 'w-4 h-4'])
                    </a>
                    <form method="POST" action="{{ route('pertanyaan.destroy', $p) }}"
                          x-data @submit.prevent="if(confirm('Hapus FAQ ini?')) $el.submit()">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                title="Hapus">
                            @include('components.ui.icons', ['icon' => 'trash', 'class' => 'w-4 h-4'])
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    @empty
    <div class="px-6 py-12 text-center">
        <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
            @include('components.ui.icons', ['icon' => 'help-circle', 'class' => 'w-6 h-6 text-slate-400'])
        </div>
        <p class="text-slate-400 text-sm">
            @if(request('kategori'))
                Tidak ada FAQ untuk kategori <strong>{{ request('kategori') }}</strong>
            @else
                Belum ada FAQ
            @endif
        </p>
        <a href="{{ route('pertanyaan.create') }}"
           class="inline-flex items-center gap-2 mt-3 px-4 py-2 bg-blue-600 text-white
                  text-sm font-medium rounded-xl hover:bg-blue-500 transition">
            @include('components.ui.icons', ['icon' => 'plus', 'class' => 'w-4 h-4'])
            Tambah FAQ Pertama
        </a>
    </div>
    @endforelse

</div>

{{-- Pagination --}}
@if($pertanyaans->hasPages())
<div class="mt-4">{{ $pertanyaans->links() }}</div>
@endif

@endsection