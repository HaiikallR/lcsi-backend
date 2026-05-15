{{-- 

LAYOUT INHERITANCE — Ini adalah "master layout" utama.
    Semua halaman yang butuh sidebar & navbar akan @extends('layouts.app').
 
    Konsep yang dipakai:
    • @yield('title')   → slot untuk judul halaman
    • @yield('content') → slot untuk konten utama
    • @stack('scripts') → slot untuk tambahan JS per halaman
    • @stack('styles')  → slot untuk tambahan CSS per halaman
 
    Komponen Blade yang dipakai di sini:
    • <x-layout.sidebar>   → sidebar navigasi (komponen terpisah)
    • <x-layout.navbar>    → navbar atas (komponen terpisah)
    • <x-ui.flash-message> → notifikasi flash (komponen terpisah)
--}}

<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
 
    {{-- @yield: diisi oleh halaman turunan dengan @section('title') --}}
    <title>@yield('title', 'Dashboard') — LCSI Admin</title>
 
    {{-- Google Fonts: Sora untuk heading, DM Sans untuk body --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
 
    {{-- Vite: Tailwind CSS & Alpine.js --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
 
    {{-- @stack('styles'): halaman turunan bisa push CSS tambahan --}}
    @stack('styles')
 
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        h1,h2,h3,h4,.font-display { font-family: 'Sora', sans-serif; }
    </style>
</head>
<body class="h-full bg-slate-50 text-slate-800" x-data="{ sidebarOpen: true }">
 
    <div class="flex h-screen overflow-hidden">
 
        {{--
            BLADE COMPONENT — x-layout.sidebar
            Komponen ini ada di: app/View/Components/Layout/Sidebar.php
            View-nya di: resources/views/components/layout/sidebar.blade.php
        --}}
        <x-layout.sidebar />
 
        {{-- Area Konten Utama --}}
        <div class="flex-1 flex flex-col overflow-hidden min-w-0">
 
            {{--
                BLADE COMPONENT — x-layout.navbar
                Menerima prop :title dari @yield
            --}}
            <x-layout.navbar :title="View::yieldContent('title', 'Dashboard')" />
 
            {{-- Scrollable Content Area --}}
            <main class="flex-1 overflow-y-auto">
                <div class="px-6 py-6 max-w-screen-2xl mx-auto">
 
                    {{--
                        BLADE COMPONENT — x-ui.flash-message
                        Otomatis tampil jika ada session('success') atau session('error')
                        Tidak perlu tulis ulang di setiap halaman
                    --}}
                    <x-ui.flash-message />
 
                    {{-- @yield('content'): diisi oleh halaman turunan --}}
                    @yield('content')
 
                </div>
            </main>
        </div>//
    </div>
 
    {{-- @stack('scripts'): halaman turunan bisa push JS tambahan --}}
    @stack('scripts')
</body>
</html>
 