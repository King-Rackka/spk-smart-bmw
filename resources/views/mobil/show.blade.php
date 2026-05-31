@extends('layouts.app')
@section('title', $mobil->nama_lengkap)

@section('content')
<div class="max-w-6xl mx-auto px-8 py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-8">
        <a href="{{ route('home') }}" class="hover:text-gray-600 transition">Home</a>
        <span>/</span>
        <a href="{{ route('mobil.index') }}" class="hover:text-gray-600 transition">Data Mobil</a>
        <span>/</span>
        <a href="{{ route('mobil.index', ['seri' => $seri->slug]) }}" class="hover:text-gray-600 transition">{{ $seri->nama }}</a>
        <span>/</span>
        <span class="text-indigo-600 font-medium">{{ $mobil->nama_lengkap }}</span>
    </nav>

    <div class="grid grid-cols-5 gap-8">

        {{-- LEFT — Image + quick specs --}}
        <div class="col-span-2 flex flex-col gap-4">

            {{-- Image --}}
            <div class="bg-gray-50 rounded-2xl overflow-hidden aspect-[16/8]">
                @if($mobil->gambar)
                    <img src="{{ asset('images/mobil/' . $mobil->gambar) }}"
                         alt="{{ $mobil->nama_lengkap }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-7xl text-gray-200">🚘</div>
                @endif
            </div>

            {{-- Quick specs --}}
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-50">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Spesifikasi Singkat</span>
                </div>
                <div class="divide-y divide-gray-50">
                    <div class="flex items-center justify-between px-5 py-3">
                        <span class="text-xs text-gray-500">Kode</span>
                        <span class="text-xs font-bold text-gray-900">{{ $mobil->kode }}</span>
                    </div>
                    <div class="flex items-center justify-between px-5 py-3">
                        <span class="text-xs text-gray-500">Tahun</span>
                        <span class="text-xs font-bold text-gray-900">{{ $mobil->tahun }}</span>
                    </div>
                    <div class="flex items-center justify-between px-5 py-3">
                        <span class="text-xs text-gray-500">Transmisi</span>
                        <span class="text-xs font-bold text-gray-900">{{ ucfirst($mobil->transmisi) }}</span>
                    </div>
                    <div class="flex items-center justify-between px-5 py-3">
                        <span class="text-xs text-gray-500">Tipe Bodi</span>
                        <span class="text-xs font-bold text-gray-900">{{ ucfirst(str_replace('_', ' ', $mobil->tipe_bodi)) }}</span>
                    </div>
                    <div class="flex items-center justify-between px-5 py-3">
                        <span class="text-xs text-gray-500">Bahan Bakar</span>
                        <span class="text-xs font-bold text-gray-900">{{ ucfirst($mobil->bahan_bakar) }}</span>
                    </div>
                </div>
            </div>

            {{-- CTA --}}
            <a href="{{ route('spk.tahap2', ['kode_bodi' => $mobil->id]) }}"
               class="w-full flex items-center justify-center gap-2 bg-indigo-600 text-white text-sm font-bold py-3.5 rounded-xl hover:bg-indigo-700 active:scale-95 transition-all">
                Pilih Mobil Ini →
            </a>
            <a href="{{ route('mobil.index', ['seri' => $seri->slug]) }}"
               class="w-full flex items-center justify-center gap-2 border border-gray-200 text-gray-600 text-sm font-medium py-3 rounded-xl hover:bg-gray-50 transition">
                ← Kembali ke {{ $seri->nama }}
            </a>
        </div>

        {{-- RIGHT — Detail --}}
        <div class="col-span-3 flex flex-col gap-6">

            {{-- Title --}}
            <div>
                <div class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full mb-3">
                    {{ $seri->nama }}
                </div>
                <h1 class="text-3xl font-black text-gray-900 mb-2">{{ $mobil->nama_lengkap }}</h1>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $mobil->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
            </div>

            {{-- Tags --}}
            <div class="flex flex-wrap gap-2">
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-600 bg-gray-100 px-3 py-1.5 rounded-full">
                    ⚙ {{ ucfirst($mobil->transmisi) }}
                </span>
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-600 bg-gray-100 px-3 py-1.5 rounded-full">
                    🚗 {{ ucfirst(str_replace('_', ' ', $mobil->tipe_bodi)) }}
                </span>
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-600 bg-gray-100 px-3 py-1.5 rounded-full">
                    ⛽ {{ ucfirst($mobil->bahan_bakar) }}
                </span>
                @if($mobil->tahun)
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-600 bg-gray-100 px-3 py-1.5 rounded-full">
                    📅 {{ $mobil->tahun }}
                </span>
                @endif
            </div>

            {{-- Nilai Kriteria Tahap 2 --}}
            @if($nilaiKriteria->count())
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-50">
                    <div class="text-sm font-bold text-gray-900">Penilaian Kriteria SMART</div>
                    <div class="text-xs text-gray-400 mt-0.5">Nilai alternatif berdasarkan kriteria Tahap 2 (skala 1–5)</div>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach($nilaiKriteria as $item)
                    <div class="px-6 py-3.5 flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $item->nama_kriteria }}</div>
                            <span class="text-[10px] font-bold {{ $item->tipe === 'cost' ? 'text-blue-600' : 'text-emerald-600' }}">
                                {{ ucfirst($item->tipe) }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            {{-- Star rating visual --}}
                            <div class="flex gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                <div class="w-2.5 h-2.5 rounded-full {{ $i <= $item->nilai ? 'bg-indigo-500' : 'bg-gray-100' }}"></div>
                                @endfor
                            </div>
                            <span class="text-sm font-black text-indigo-600 min-w-[1.5rem] text-right">{{ $item->nilai }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Mobil lain di seri ini --}}
            @if($mobilLain->count())
            <div>
                <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Mobil Lain di {{ $seri->nama }}</div>
                <div class="flex flex-col gap-2">
                    @foreach($mobilLain as $lain)
                    <a href="{{ route('mobil.show', $lain->id) }}"
                       class="flex items-center gap-3 p-3 bg-white border border-gray-100 rounded-xl hover:border-indigo-100 hover:shadow-sm transition group">
                        <div class="w-14 h-10 bg-gray-50 rounded-lg overflow-hidden flex-shrink-0">
                            @if($lain->gambar)
                                <img src="{{ asset('images/mobil/' . $lain->gambar) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-xl">🚗</div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-semibold text-gray-900 truncate group-hover:text-indigo-600 transition">{{ $lain->nama_lengkap }}</div>
                            <div class="text-xs text-gray-400">{{ ucfirst($lain->tipe_bodi) }} · {{ ucfirst($lain->bahan_bakar) }}</div>
                        </div>
                        <span class="text-gray-300 group-hover:text-indigo-400 transition">→</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection