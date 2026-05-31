@extends('layouts.app')
@section('title', 'Data Mobil BMW')

@section('content')
<div class="max-w-6xl mx-auto px-8 py-10">

    {{-- Header --}}
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-gray-600 transition">Home</a>
        <span>/</span>
        <span class="text-indigo-600 font-medium">Data Mobil</span>
    </nav>
    <h1 class="text-4xl font-black text-gray-900 mb-1">
        Data Mobil <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">BMW</span>
    </h1>
    <p class="text-sm text-gray-500 mb-8">Jelajahi koleksi lengkap BMW berdasarkan seri</p>

    {{-- Filter Tabs --}}
    <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-1">
        <a href="{{ route('mobil.index') }}"
           class="flex-shrink-0 text-sm font-semibold px-4 py-2 rounded-xl transition
                  {{ !request('seri') ? 'bg-indigo-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-indigo-200 hover:text-indigo-600' }}">
            Semua
        </a>
        @foreach ($seris as $seri)
        <a href="{{ route('mobil.index', ['seri' => $seri->slug]) }}"
           class="flex-shrink-0 text-sm font-semibold px-4 py-2 rounded-xl transition
                  {{ request('seri') === $seri->slug ? 'bg-indigo-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-indigo-200 hover:text-indigo-600' }}">
            Seri {{ str_replace('BMW Seri ', '', $seri->nama) }}
        </a>
        @endforeach
    </div>

    {{-- Active seri label --}}
    @if(request('seri'))
    <div class="mb-6">
        <p class="text-xs font-medium text-indigo-600 uppercase tracking-widest mb-1">Koleksi Mobil</p>
        <h2 class="text-2xl font-black text-gray-900">{{ $seris->firstWhere('slug', request('seri'))?->nama ?? 'BMW' }}</h2>
        <p class="text-sm text-gray-400 mt-0.5">{{ $seris->firstWhere('slug', request('seri'))?->deskripsi }}</p>
    </div>
    @else
    <div class="mb-6">
        <p class="text-xs font-medium text-indigo-600 uppercase tracking-widest mb-1">Koleksi Mobil</p>
        <h2 class="text-2xl font-black text-gray-900">Pilihan Mobil BMW</h2>
        <p class="text-sm text-gray-400 mt-0.5">Temukan mobil impian Anda dari lineup BMW premium kami</p>
    </div>
    @endif

    {{-- Total count --}}
    <p class="text-xs text-gray-400 mb-4">
        Menampilkan {{ $mobils->firstItem() }}–{{ $mobils->lastItem() }} dari {{ $mobils->total() }} model
    </p>

    {{-- Grid --}}
    @if($mobils->isEmpty())
        <div class="text-center py-20">
            <div class="text-5xl mb-4">🚗</div>
            <p class="text-gray-400 text-sm">Belum ada data mobil untuk seri ini.</p>
        </div>
    @else
    <div class="grid grid-cols-3 gap-5">
        @foreach ($mobils as $mobil)
        <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden hover:shadow-md hover:border-indigo-100 transition group">
            <div class="relative aspect-[16/9] bg-gray-50 overflow-hidden">
                @if($mobil->gambar)
                    <img src="{{ asset('images/mobil/' . $mobil->gambar) }}"
                         alt="{{ $mobil->nama_lengkap }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                @else
                    <div class="w-full h-full flex items-center justify-center text-5xl text-gray-200">🚘</div>
                @endif
            </div>
            <div class="p-5">
                <div class="text-xs font-bold text-indigo-500 mb-1">{{ $mobil->seri_nama }}</div>
                <h3 class="text-base font-black text-gray-900 mb-1">{{ $mobil->nama_lengkap }}</h3>
                <p class="text-xs text-gray-400 leading-relaxed mb-4 line-clamp-2">{{ $mobil->deskripsi ?? '-' }}</p>
                <div class="flex flex-wrap gap-1.5 mb-4">
                    <span class="inline-flex items-center gap-1 text-[10px] font-medium text-gray-500 bg-gray-50 px-2.5 py-1 rounded-full border border-gray-100">
                        ⚙ {{ ucfirst($mobil->transmisi) }}
                    </span>
                    <span class="inline-flex items-center gap-1 text-[10px] font-medium text-gray-500 bg-gray-50 px-2.5 py-1 rounded-full border border-gray-100">
                        🚗 {{ ucfirst(str_replace('_', ' ', $mobil->tipe_bodi)) }}
                    </span>
                    <span class="inline-flex items-center gap-1 text-[10px] font-medium text-gray-500 bg-gray-50 px-2.5 py-1 rounded-full border border-gray-100">
                        ⛽ {{ ucfirst($mobil->bahan_bakar) }}
                    </span>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('mobil.show', $mobil->id) }}"
                       class="flex-1 flex items-center justify-center gap-1.5 border border-gray-200 text-gray-600 text-xs font-semibold py-2 rounded-xl hover:border-indigo-200 hover:text-indigo-600 transition">
                        👁 Lihat detail
                    </a>
                    <a href="{{ route('spk.tahap2', ['kode_bodi' => $mobil->id]) }}"
                       class="flex-1 flex items-center justify-center gap-1.5 bg-indigo-600 text-white text-xs font-semibold py-2 rounded-xl hover:bg-indigo-700 transition">
                        ✓ Pilih Mobil
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($mobils->hasPages())
    <div class="mt-8 flex items-center justify-center gap-1">
        {{-- Prev --}}
        @if($mobils->onFirstPage())
            <span class="px-3 py-2 text-sm text-gray-300 border border-gray-100 rounded-xl cursor-not-allowed">← Prev</span>
        @else
            <a href="{{ $mobils->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-600 border border-gray-200 rounded-xl hover:border-indigo-200 hover:text-indigo-600 transition">← Prev</a>
        @endif

        {{-- Page numbers --}}
        @foreach($mobils->getUrlRange(1, $mobils->lastPage()) as $page => $url)
            @if($page == $mobils->currentPage())
                <span class="px-3.5 py-2 text-sm font-bold text-white bg-indigo-600 rounded-xl">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="px-3.5 py-2 text-sm text-gray-600 border border-gray-200 rounded-xl hover:border-indigo-200 hover:text-indigo-600 transition">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Next --}}
        @if($mobils->hasMorePages())
            <a href="{{ $mobils->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-600 border border-gray-200 rounded-xl hover:border-indigo-200 hover:text-indigo-600 transition">Next →</a>
        @else
            <span class="px-3 py-2 text-sm text-gray-300 border border-gray-100 rounded-xl cursor-not-allowed">Next →</span>
        @endif
    </div>
    @endif
    @endif

</div>

{{-- ═══ INFO TAMBAHAN ═══ --}}
<div class="border-t border-gray-100 mt-4">
    <div class="max-w-6xl mx-auto px-8 py-16">

        {{-- Keunggulan --}}
        <div class="mb-14">
            <p class="text-xs font-bold text-indigo-600 uppercase tracking-widest mb-2">Keunggulan</p>
            <h2 class="text-2xl font-black text-gray-900 mb-1">
                Kenapa Memilih <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">BMW</span>?
            </h2>
            <p class="text-sm text-gray-400 mb-8">Dibalik setiap BMW tersimpan passion, teknologi, dan keunggulan tanpa kompromi.</p>

            <div class="grid grid-cols-4 gap-4">
                @foreach([
                    ['svg'=>'M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z', 'title'=>'Desain Premium',       'sub'=>'Siluet ikonik dengan proporsi sempurna, setiap lekukan dirancang untuk memukau dan aerodinamis.'],
                    ['svg'=>'M12 2a10 10 0 100 20A10 10 0 0012 2zm0 3a7 7 0 110 14A7 7 0 0112 5zm0 2a5 5 0 100 10A5 5 0 0012 7z', 'title'=>'Teknologi Canggih',    'sub'=>'BMW iDrive generasi terbaru, layar curved, dan AI driving assistant untuk pengalaman berkendara masa depan.'],
                    ['svg'=>'M13 2L3 14h9l-1 8 10-12h-9l1-8z', 'title'=>'Performa Tinggi',       'sub'=>'Mesin TwinPower Turbo bertenaga tinggi dengan respons spontan dan sensasi berkendara sporty yang tak tertandingi.'],
                    ['svg'=>'M12 2C8 2 5 5 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-4-3-7-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z', 'title'=>'Kenyamanan Berkendara','sub'=>'Kabin mewah dengan material premium, sistem suspensi adaptif, dan isolasi suara kelas satu untuk kenyamanan sempurna.'],
                ] as $item)
                <div class="bg-white border border-blue-100 rounded-2xl p-5 hover:shadow-sm hover:border-blue-300 transition text-center">
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['svg'] }}"/>
                        </svg>
                    </div>
                    <div class="text-sm font-bold text-gray-900 mb-1.5">{{ $item['title'] }}</div>
                    <div class="text-xs text-gray-400 leading-relaxed">{{ $item['sub'] }}</div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Platform Stats --}}
        <div class="rounded-2xl px-12 py-12" style="background: linear-gradient(135deg, #1e3a5f 0%, #1e40af 50%, #3b82f6 100%)">
            <div class="text-center mb-10">
                <h2 class="text-xl font-black text-white mb-1">Platform SPK BMW Kami</h2>
                <p class="text-sm text-blue-200">Sistem rekomendasi cerdas berbasis metode SMART</p>
            </div>
            <div class="grid grid-cols-4 gap-4">
                @foreach([
                    ['svg'=>'M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h11a2 2 0 012 2v3m-4 12a2 2 0 002 2h6a2 2 0 002-2v-6a2 2 0 00-2-2h-6a2 2 0 00-2 2v6z', 'val'=>'20+',  'label'=>'Model BMW'],
                    ['svg'=>'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z', 'val'=>'5',     'label'=>'Kategori Mobil'],
                    ['svg'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'val'=>'200+', 'label'=>'Pengguna Aktif'],
                    ['svg'=>'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z', 'val'=>'SMART','label'=>'Metode Rekomendasi'],
                ] as $stat)
                <div class="border border-blue-400/30 bg-white/10 rounded-xl px-6 py-5 text-center">
                    <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <svg class="w-5 h-5 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['svg'] }}"/>
                        </svg>
                    </div>
                    <div class="text-2xl font-black text-blue-200 mb-1">{{ $stat['val'] }}</div>
                    <div class="text-xs text-blue-300">{{ $stat['label'] }}</div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

@endsection