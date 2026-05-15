@extends('layouts.app')
@section('title', 'Edit FAQ')
@section('content')

<x-ui.form-card title="Edit FAQ" :back-route="route('pertanyaan.index')">

    <form method="POST" action="{{ route('pertanyaan.update', $pertanyaan) }}" id="form">
        @csrf
        @method('PUT')

        <div class="space-y-5">

            {{-- Kategori --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Kategori</label>
                <input
                    type="text"
                    name="kategori"
                    value="{{ old('kategori', $pertanyaan->kategori) }}"
                    list="kategori-list"
                    placeholder="Contoh: Layanan, Pembayaran, Teknis..."
                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm
                           focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                >
                <datalist id="kategori-list">
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat }}">
                    @endforeach
                </datalist>
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
                    value="{{ old('pertanyaan', $pertanyaan->pertanyaan) }}"
                    required
                    class="w-full px-4 py-2.5 bg-white border rounded-xl text-sm text-slate-800
                           focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                           {{ $errors->has('pertanyaan') ? 'border-red-300' : 'border-slate-200' }}"
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
                    class="w-full px-4 py-2.5 bg-white border rounded-xl text-sm text-slate-800
                           focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                           {{ $errors->has('jawaban') ? 'border-red-300' : 'border-slate-200' }}"
                >{{ old('jawaban', $pertanyaan->jawaban) }}</textarea>
                @error('jawaban')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Info terakhir diupdate --}}
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                <p class="text-xs text-slate-400 mb-2 font-medium uppercase tracking-wide">Info</p>
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div>
                        <span class="text-slate-400">Dibuat:</span>
                        <span class="text-slate-600 ml-1">
                            {{ $pertanyaan->created_at->format('d M Y') }}
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400">Diupdate:</span>
                        <span class="text-slate-600 ml-1">
                            {{ $pertanyaan->updated_at->format('d M Y') }}
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </form>

    <x-slot:actions>
        <button form="form" type="submit"
                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white
                       text-sm font-semibold rounded-xl transition">
            Simpan Perubahan
        </button>
        <a href="{{ route('pertanyaan.index') }}"
           class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600
                  text-sm rounded-xl transition hover:border-slate-300">
            Batal
        </a>
    </x-slot:actions>

</x-ui.form-card>

@endsection