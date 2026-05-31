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

    <div class="min-h-screen flex flex-col justify-center items-center px-4 py-12">
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-lg font-bold text-gray-900 mb-8">
            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="10" stroke="#4f46e5" stroke-width="2"/>
                <path d="M12 2 L12 22 M2 12 L22 12" stroke="#4f46e5" stroke-width="2"/>
                <circle cx="12" cy="12" r="3" fill="#4f46e5"/>
            </svg>
            Bimmer<span class="text-indigo-600">Guide</span>
        </a>

        <div class="w-full max-w-md bg-white border border-gray-100 rounded-2xl shadow-sm p-8">
            {{ $slot }}
        </div>
    </div>

</body>
</html>