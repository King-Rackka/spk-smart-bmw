<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BimmerGuide') — SPK Pemilihan BMW</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

    {{-- ═══ NAVBAR ═══ --}}
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-8 h-14 flex items-center justify-between">

            {{-- Logo (kiri) --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-base font-bold text-gray-900 hover:opacity-80 transition flex-shrink-0">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="10" stroke="#4f46e5" stroke-width="2"/>
                    <path d="M12 2 L12 22 M2 12 L22 12" stroke="#4f46e5" stroke-width="2"/>
                    <circle cx="12" cy="12" r="3" fill="#4f46e5"/>
                </svg>
                Bimmer<span class="text-indigo-600">Guide</span>
            </a>

            {{-- Nav + Auth (semua di kanan) --}}
            <div class="flex items-center gap-1">

                {{-- Link utama --}}
                <a href="{{ route('home') }}"
                   class="text-sm px-3 py-2 rounded-lg transition
                          {{ request()->routeIs('home') ? 'text-indigo-600 font-semibold bg-indigo-50' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                    Home
                </a>
                <a href="{{ route('mobil.index') }}"
                   class="text-sm px-3 py-2 rounded-lg transition
                          {{ request()->routeIs('mobil.*') ? 'text-indigo-600 font-semibold bg-indigo-50' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                    Data Mobil
                </a>

                {{-- Separator --}}
                <div class="w-px h-5 bg-gray-100 mx-1"></div>

                @guest
                    {{-- Belum login: Login button saja --}}
                    <a href="{{ route('login') }}"
                       class="text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-xl transition active:scale-95">
                        Login
                    </a>
                @else
                    {{-- Sudah login: Mulai Analisis + Avatar --}}
                    <a href="{{ route('spk.tahap1') }}"
                       class="text-sm font-semibold px-4 py-2 rounded-xl transition
                              {{ request()->routeIs('spk.*') ? 'bg-indigo-600 text-white' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100' }}">
                        Mulai Analisis →
                    </a>

                    {{-- Avatar dropdown --}}
                    <div class="relative ml-1" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="w-9 h-9 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold hover:bg-indigo-700 transition active:scale-95">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </button>

                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             @click.outside="open = false"
                             class="absolute right-0 mt-2 w-52 bg-white border border-gray-100 rounded-2xl shadow-lg py-1 z-50"
                             style="display:none">

                            {{-- User info --}}
                            <div class="px-4 py-3 border-b border-gray-50">
                                <div class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</div>
                                @if(auth()->user()->role === 'admin')
                                <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full mt-1.5 inline-block">Admin</span>
                                @endif
                            </div>

                            {{-- Menu --}}
                            <div class="py-1">
                                <a href="{{ route('riwayat.index') }}"
                                class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 transition">
                                    📋 Riwayat Analisis
                                </a>
                                @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}"
                                   class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 transition">
                                    ⚙️ Dashboard Admin
                                </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition">
                                        ↩ Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endguest
            </div>

        </div>
    </nav>

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="max-w-6xl mx-auto px-8 pt-4">
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm px-4 py-3 rounded-xl flex items-center gap-2">
            ✓ {{ session('success') }}
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="max-w-6xl mx-auto px-8 pt-4">
        <div class="bg-red-50 border border-red-100 text-red-600 text-sm px-4 py-3 rounded-xl flex items-center gap-2">
            ✕ {{ session('error') }}
        </div>
    </div>
    @endif

    {{-- Main --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-gray-100 mt-10">
        <div class="max-w-6xl mx-auto px-8 py-6 flex items-center justify-between">
            <p class="text-xs text-gray-400">© {{ date('Y') }} BMW SPK — Sistem Pendukung Keputusan dibuat dengan metode SMART</p>
            <p class="text-xs text-gray-300">Laravel · Tailwind CSS</p>
        </div>
    </footer>

</body>
</html>