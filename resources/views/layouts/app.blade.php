<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BimmerGuide') — SPK Pemilihan BMW</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

    {{-- Navbar --}}
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-8 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-lg font-medium text-gray-900">
                Bimmer<span class="text-indigo-600">Guide</span>
            </a>
            <div class="flex items-center gap-6">
                <a href="#metode" class="text-sm text-gray-500 hover:text-gray-900 transition">Tentang metode</a>
                <a href="#datamobil" class="text-sm text-gray-500 hover:text-gray-900 transition">Data Mobil</a>
                <a href="{{ route('spk.tahap1') }}"
                   class="text-sm font-medium text-indigo-700 bg-indigo-50 px-4 py-2 rounded-lg hover:bg-indigo-100 transition">
                    Mulai Analisis →
                </a>
            </div>
        </div>
    </nav>

    {{-- Main --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-gray-100">
        <div class="max-w-6xl mx-auto px-8 py-6 flex items-center justify-between">
            <p class="text-xs text-gray-400">© {{ date('Y') }} BimmerGuide — Sistem Pendukung Keputusan Pemilihan BMW</p>
            <p class="text-xs text-gray-400">Metode SMART · Laravel 12</p>
        </div>
    </footer>

</body>
</html>
