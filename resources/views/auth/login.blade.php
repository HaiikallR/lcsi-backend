@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<div class="min-h-screen flex">

    {{-- Kiri — Branding --}}
    <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-blue-600 to-blue-900
                flex-col justify-between p-12">
        <div>
            {{-- Logo --}}
            <div class="w-12 h-12 bg-white/20 backdrop-blur rounded-2xl
                        flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>

            <h1 class="mt-8 text-4xl font-bold text-white leading-tight"
                style="font-family: 'Sora', sans-serif;">
                LCSI<br>Admin Panel
            </h1>
            <p class="mt-4 text-blue-200 text-base leading-relaxed max-w-xs">
                Sistem manajemen terpadu untuk layanan internet pelanggan LCSI.
            </p>
        </div>

        {{-- Fitur List --}}
        <div class="space-y-3">
            @foreach([
                'Manajemen Pelanggan & Teknisi',
                'Monitoring Tiket & Perangkat',
                'Verifikasi Pembayaran',
                'Laporan Keuangan',
                'Push Notification via FCM',
            ] as $fitur)
            <div class="flex items-center gap-3 text-blue-100">
                <div class="w-5 h-5 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <span class="text-sm">{{ $fitur }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Kanan — Form Login --}}
    <div class="flex-1 flex items-center justify-center p-8 bg-slate-900">
        <div class="w-full max-w-sm">

            {{-- Mobile Logo --}}
            <div class="lg:hidden text-center mb-8">
                <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                              d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h2 class="text-white font-bold text-xl" style="font-family: 'Sora', sans-serif;">
                    LCSI Admin
                </h2>
            </div>

            <h2 class="text-2xl font-bold text-white mb-1" style="font-family: 'Sora', sans-serif;">
                Selamat Datang
            </h2>
            <p class="text-slate-400 text-sm mb-8">
                Masuk ke panel administrasi LCSI
            </p>

            {{-- Flash Message (logout success) --}}
            @if(session('success'))
            <div class="mb-5 flex items-center gap-3 p-4 bg-emerald-500/10 border border-emerald-500/20
                        rounded-xl text-emerald-400 text-sm">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            {{-- Error --}}
            @if($errors->any())
            <div class="mb-5 flex items-center gap-3 p-4 bg-red-500/10 border border-red-500/20
                        rounded-xl text-red-400 text-sm">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $errors->first() }}
            </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">
                        Email
                    </label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        placeholder="admin@lcsi.com"
                        class="w-full px-4 py-3 bg-slate-800 border rounded-xl text-white text-sm
                               placeholder-slate-500 focus:outline-none focus:ring-2
                               focus:ring-blue-500 focus:border-transparent transition
                               {{ $errors->has('email') ? 'border-red-500' : 'border-slate-700' }}"
                    >
                </div>

                {{-- Password --}}
               {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">
                        Password
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            name="password"
                            required
                            placeholder="••••••••"
                            class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-xl
                                text-white text-sm placeholder-slate-500 focus:outline-none
                                focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                transition"
                        >
                    </div>
                </div>
                {{-- Remember Me --}}
                <div class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="remember"
                        id="remember"
                        class="w-4 h-4 rounded border-slate-600 bg-slate-800
                               text-blue-500 focus:ring-blue-500 focus:ring-offset-slate-900"
                    >
                    <label for="remember" class="text-sm text-slate-400">
                        Ingat saya
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full py-3 bg-blue-600 hover:bg-blue-500 text-white font-semibold
                               rounded-xl transition-all duration-150 text-sm shadow-lg
                               shadow-blue-600/20 hover:shadow-blue-500/30">
                    Masuk ke Dashboard
                </button>
            </form>

        </div>
    </div>
</div>
@endsection