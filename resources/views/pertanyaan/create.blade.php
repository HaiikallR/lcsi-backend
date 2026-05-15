@extends('layouts.app')
@section('title', 'Tambah FAQ')
@section('content')

<x-ui.form-card title="Tambah FAQ Baru" :back-route="route('pertanyaan.index')">

    <form method="POST" action="{{ route('pertanyaan.store') }}" id="form">
        @csrf

        <div class="space-y-5">

            {{-- Kategori --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                    Kategori
                </label>
                {{-- Input dengan datalist untuk suggestion --}}
                <input
                    type="text"
                    name="kategori"
                    value="{{ old('kategori') }}"
                    list="kategori-list"
                    placeholder="Contoh: Layanan, Pembayaran, Teknis..."
                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm
                           text-slate-800 placeholder-slate-300 focus:outline-none
                           focus:ring-2 focus:ring-blue-500 transition"
                >
                {{-- Suggestion dari kategori yang sudah ada --}}
                <datalist id="kategori-list">
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat }}">
                    @endforeach
                </datalist>
                <p class="mt-1.5 text-xs text-slate-400">
                    Pilih yang sudah ada atau ketik kategori baru
                </p>
                @error('kategori')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Pertanyaan --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                    Pertanyaan <span class="text-red-400">*</span>
                </label>
                <input
                    type="text"
                    name="pertanyaan"
                    value="{{ old('pertanyaan') }}"
                    required
                    placeholder="Contoh: Bagaimana cara melakukan pembayaran tagihan?"
                    class="w-full px-4 py-2.5 bg-white border rounded-xl text-sm text-slate-800
                           placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500
                           transition {{ $errors->has('pertanyaan') ? 'border-red-300' : 'border-slate-200' }}"
                >
                @error('pertanyaan')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jawaban --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                    Jawaban <span class="text-red-400">*</span>
                </label>
                <textarea
                    name="jawaban"
                    rows="6"
                    required
                    placeholder="Tulis jawaban yang jelas dan mudah dipahami pelanggan..."
                    class="w-full px-4 py-2.5 bg-white border rounded-xl text-sm text-slate-800
                           placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500
                           transition {{ $errors->has('jawaban') ? 'border-red-300' : 'border-slate-200' }}"
                >{{ old('jawaban') }}</textarea>
                <p class="mt-1.5 text-xs text-slate-400">
                    Gunakan enter untuk membuat paragraf baru
                </p>
                @error('jawaban')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Preview --}}
            <div x-data="{ pertanyaan: '', jawaban: '' }" class="space-y-3">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">
                    Preview Tampilan
                </p>
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                    <p class="font-medium text-slate-700 text-sm">
                        {{ old('pertanyaan') ?: 'Pertanyaan akan tampil di sini...' }}
                    </p>
                    @if(old('jawaban'))
                    <p class="mt-2 text-sm text-slate-500 border-l-2 border-blue-200 pl-3">
                        {{ old('jawaban') }}
                    </p>
                    @endif
                </div>
            </div>

        </div>
    </form>

    <x-slot:actions>
        <button form="form" type="submit"
                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white
                       text-sm font-semibold rounded-xl transition">
            Simpan FAQ
        </button>
        <a href="{{ route('pertanyaan.index') }}"
           class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600
                  text-sm rounded-xl transition hover:border-slate-300">
            Batal
        </a>
    </x-slot:actions>

</x-ui.form-card>

@endsection