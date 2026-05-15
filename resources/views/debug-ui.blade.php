@extends('dashboard.index')

@section('title', 'Preview UI Components')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Card Contoh --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h3 class="text-lg font-bold mb-4">Statistik Perangkat</h3>
            <p class="text-slate-500">Ini adalah contoh konten di dalam layout yang kamu buat.</p>
            <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                <span class="text-blue-600 font-medium">Status: Online</span>
            </div>
        </div>

        {{-- Card Contoh Lain --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h3 class="text-lg font-bold mb-4">Informasi Admin</h3>
            <p class="text-slate-500">Email: {{ Auth::user()->email ?? 'admin@lcsi.com' }}</p>
        </div>
    </div>
@endsection