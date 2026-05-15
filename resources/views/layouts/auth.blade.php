{{--
    layouts/auth.blade.php
    ─────────────────────────────────────────────────────────────────────────
    Layout khusus halaman auth (login).
    Terpisah dari layouts/app.blade.php karena tidak butuh sidebar/navbar.
--}}
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login') — LCSI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>body { font-family: 'DM Sans', sans-serif; }</style>
</head>
<body class="h-full bg-slate-900">
    @yield('content')
</body>
</html>