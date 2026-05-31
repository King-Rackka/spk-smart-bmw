@extends('layouts.app')
@section('title', 'Riwayat Analisis')

@section('content')
<div class="max-w-6xl mx-auto px-8 py-10">

    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-gray-600">Home</a>
        <span>/</span>
        <span class="text-indigo-600 font-medium">Riwayat</span>
    </nav>

    <h1 class="text-3xl font-black text-gray-900 mb-1">
        Riwayat <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">Analisis</span>
    </h1>
    <p class="text-sm text-gray-500 mb-8">Semua hasil analisis SMART yang pernah Anda lakukan</p>

    @if($riwayat->isEmpty())
        <div class="text-center py-24 bg-white border border-gray-100 rounded-2xl">
            <div class="text-5xl mb-4">📋</div>
            <p class="text-sm font-semibold text-gray-500 mb-1">Belum ada riwayat analisis</p>
            <p class="text-xs text-gray-400 mb-6">Mulai analisis pertama Anda sekarang</p>
            <a href="{{ route('spk.tahap1') }}"
               class="inline-flex items-center gap-2 bg-indigo-600 text-white text-sm font-semibold px-6 py-3 rounded-xl hover:bg-indigo-700 transition">
                Mulai Analisis →
            </a>
        </div>
    @else

        <div class="flex flex-col gap-3">
            @foreach($riwayat as $item)
            @php
                $ranked = json_decode($item->ranking_tahap2, true);
                $skipped = is_null($item->bobot_tahap1);
            @endphp
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden hover:shadow-sm hover:border-indigo-100 transition group">
                <div class="flex items-center gap-5 p-5">

                    {{-- Gambar --}}
                    <div class="w-24 h-16 bg-gray-50 rounded-xl overflow-hidden flex-shrink-0">
                        @if($item->model_gambar)
                            <img src="{{ asset('images/mobil/'.$item->model_gambar) }}"
                                 class="w-full h-full object-cover" alt="{{ $item->model_nama }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-2xl text-gray-200">🚗</div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-bold text-indigo-500">{{ $item->seri_nama }}</span>
                            @if($skipped)
                            <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">Tahap 1 Dilewati</span>
                            @endif
                        </div>
                        <div class="text-base font-black text-gray-900 truncate">🏆 {{ $item->model_nama }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">
                            {{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i') : '-' }}
                        </div>
                    </div>

                    {{-- Skor --}}
                    <div class="text-center flex-shrink-0 px-4">
                        <div class="text-2xl font-black text-indigo-600">{{ number_format($item->skor_akhir*100,1) }}%</div>
                        <div class="text-[10px] text-gray-400">Skor SMART</div>
                    </div>

                    {{-- Ranking mini --}}
                    @if($ranked)
                    <div class="hidden md:flex flex-col gap-1 flex-shrink-0 w-36">
                        @foreach(array_slice($ranked, 0, 3) as $ri => $r)
                        <div class="flex items-center gap-1.5 text-[10px]">
                            <span>{{ ['🥇','🥈','🥉'][$ri] }}</span>
                            <span class="truncate {{ $ri===0?'font-bold text-gray-900':'text-gray-400' }}">{{ $r['nama'] }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    {{-- Actions --}}
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <a href="{{ route('riwayat.detail', $item->id) }}"
                           class="text-xs font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 px-3 py-2 rounded-xl transition">
                            Detail
                        </a>
                        <a href="{{ route('spk.pdf', $item->id) }}"
                           class="text-xs font-semibold text-red-500 bg-red-50 hover:bg-red-100 px-3 py-2 rounded-xl transition">
                            PDF
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($riwayat->hasPages())
        <div class="mt-6 flex items-center justify-center gap-1">
            @if($riwayat->onFirstPage())
                <span class="px-3 py-2 text-sm text-gray-300 border border-gray-100 rounded-xl cursor-not-allowed">← Prev</span>
            @else
                <a href="{{ $riwayat->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-600 border border-gray-200 rounded-xl hover:border-indigo-200 hover:text-indigo-600 transition">← Prev</a>
            @endif
            @foreach($riwayat->getUrlRange(1, $riwayat->lastPage()) as $page => $url)
                @if($page == $riwayat->currentPage())
                    <span class="px-3.5 py-2 text-sm font-bold text-white bg-indigo-600 rounded-xl">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="px-3.5 py-2 text-sm text-gray-600 border border-gray-200 rounded-xl hover:border-indigo-200 transition">{{ $page }}</a>
                @endif
            @endforeach
            @if($riwayat->hasMorePages())
                <a href="{{ $riwayat->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-600 border border-gray-200 rounded-xl hover:border-indigo-200 hover:text-indigo-600 transition">Next →</a>
            @else
                <span class="px-3 py-2 text-sm text-gray-300 border border-gray-100 rounded-xl cursor-not-allowed">Next →</span>
            @endif
        </div>
        @endif
    @endif
</div>
@endsection