<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SELA – Kerja Kelompok Adil, Tanpa Drama.')</title>
    <meta name="description" content="@yield('description', 'SELA membagi tugas dengan cerdas menggunakan AI, memantau kontribusi setiap anggota, dan memastikan kolaborasi mahasiswa berjalan transparan.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Mono+One&family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/sela.png') }}">
    <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('images/sela.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="m-0 p-0">
    @yield('content')
</body>
</html>
