@extends('layouts.app')
@section('title', 'Hasil Analisis SMART')

@section('content')
<div class="max-w-6xl mx-auto px-8 py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-gray-600">Home</a><span>/</span>
        @if(isset($fromRiwayat))
            <a href="{{ route('riwayat.index') }}" class="hover:text-gray-600">Riwayat</a><span>/</span>
        @endif
        <span class="text-indigo-600 font-medium">Hasil Analisis</span>
    </nav>

    {{-- Header --}}
    <div class="flex items-start justify-between mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-900 mb-1">Hasil Analisis <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">SMART</span></h1>
            <p class="text-sm text-gray-500">{{ $seri->nama }} — {{ now()->format('d M Y') }}</p>
            @if($skipped)
            <span class="inline-flex items-center gap-1 mt-2 text-[10px] font-bold text-amber-600 bg-amber-50 border border-amber-100 px-2.5 py-1 rounded-full">
                ↷ Tahap 1 Dilewati — Seri dipilih manual
            </span>
            @endif
        </div>
        <div class="flex gap-2">
            @if(isset($hasilId))
            <a href="{{ route('spk.pdf', $hasilId) }}"
               class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition active:scale-95">
                📄 Cetak PDF
            </a>
            @endif
            <a href="{{ route('spk.reset') }}"
               class="flex items-center gap-2 border border-gray-200 text-gray-600 text-sm font-medium px-5 py-2.5 rounded-xl hover:bg-gray-50 transition">
                ↺ Analisis Baru
            </a>
        </div>
    </div>

    {{-- WINNER CARD --}}
    <div class="rounded-2xl mb-6 overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 40%, #1d4ed8 100%)">
        <div class="px-8 py-8 flex items-center gap-8">
            <div class="text-6xl">🏆</div>
            <div class="flex-1">
                <div class="text-xs font-bold text-blue-300 uppercase tracking-widest mb-1">Rekomendasi Terbaik</div>
                <h2 class="text-2xl font-black text-white mb-1">{{ $ranked[0]['nama'] }}</h2>
                <div class="text-sm text-blue-200">{{ $seri->nama }} · {{ $ranked[0]['tahun'] ?? '' }}</div>
                <div class="mt-3 flex items-center gap-3">
                    <div class="h-2 bg-white/20 rounded-full overflow-hidden flex-1 max-w-xs">
                        <div class="h-full bg-white rounded-full" style="--progress: {{ number_format($ranked[0]['skor']*100, 2) }}%; width: var(--progress);"></div>
                    </div>
                    <span class="text-2xl font-black text-white">{{ number_format($ranked[0]['skor']*100,2) }}%</span>
                </div>
            </div>
            @if(isset($bestDetail) && $bestDetail?->gambar)
            <img src="{{ asset('images/mobil/'.$bestDetail->gambar) }}"
                 class="w-52 h-36 object-cover rounded-xl opacity-90" alt="{{ $ranked[0]['nama'] }}">
            @endif
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6">

        {{-- LEFT (2/3) --}}
        <div class="col-span-2 flex flex-col gap-6">

            {{-- Ranking --}}
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-50">
                    <div class="text-sm font-bold text-gray-900">Ranking Lengkap</div>
                    <div class="text-xs text-gray-400 mt-0.5">Urutan dari skor tertinggi ke terendah</div>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach($ranked as $i => $item)
                    <div class="px-6 py-4 flex items-center gap-4 {{ $i===0?'bg-indigo-50/30':'' }}">
                        <div class="text-xl w-8 text-center">{{ ['🥇','🥈','🥉'][$i] ?? '#'.($i+1) }}</div>
                        <div class="flex-1">
                            <div class="text-sm font-bold {{ $i===0?'text-indigo-700':'text-gray-900' }}">{{ $item['nama'] }}</div>
                            <div class="text-xs text-gray-400">{{ $item['tahun'] ?? '' }}</div>
                            <div class="mt-1.5 h-1.5 bg-gray-100 rounded-full overflow-hidden max-w-xs">
                                <div class="h-full {{ $i === 0 ? 'bg-indigo-500' : 'bg-gray-300' }} rounded-full"
                                    style="--progress: {{ number_format($item['skor']*100, 2) }}%; width: var(--progress);">
                                </div>
                            </div>
                        </div>
                        <div class="text-lg font-black {{ $i===0?'text-indigo-600':'text-gray-400' }}">
                            {{ number_format($item['skor']*100,2) }}%
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Breakdown Utility --}}
            @if(!empty($utility))
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-50">
                    <div class="text-sm font-bold text-gray-900">Breakdown Utility per Kriteria</div>
                    <div class="text-xs text-gray-400 mt-0.5">Nilai utility ternormalisasi (0–1) per alternatif per kriteria</div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="border-b border-gray-50 bg-gray-50/50">
                                <th class="px-5 py-3 text-left font-semibold text-gray-500">Model</th>
                                @foreach($kriteria as $k)
                                <th class="px-3 py-3 text-center font-semibold {{ is_array($k)?($k['tipe']==='cost'?'text-blue-500':'text-emerald-500'):($k->tipe==='cost'?'text-blue-500':'text-emerald-500') }}">
                                    {{ is_array($k)?$k['nama']:$k->nama }}
                                    <div class="text-[9px] font-normal opacity-60">{{ is_array($k)?$k['tipe']:$k->tipe }}</div>
                                </th>
                                @endforeach
                                <th class="px-3 py-3 text-center font-semibold text-gray-400">Skor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($ranked as $i => $item)
                            <tr class="{{ $i===0?'bg-indigo-50/20':'' }}">
                                <td class="px-5 py-3 font-semibold text-gray-900">{{ $item['nama'] }}</td>
                                @foreach($kriteria as $k)
                                @php $kode = is_array($k)?$k['kode']:$k->kode; $u = $utility[$item['id']][$kode] ?? 0; @endphp
                                <td class="px-3 py-3 text-center">
                                    <span class="inline-flex items-center justify-center w-12 h-6 rounded text-[10px] font-bold
                                        {{ $u >= 0.75?'bg-emerald-50 text-emerald-600':($u >= 0.5?'bg-blue-50 text-blue-600':($u >= 0.25?'bg-amber-50 text-amber-600':'bg-red-50 text-red-500')) }}">
                                        {{ number_format($u,3) }}
                                    </span>
                                </td>
                                @endforeach
                                <td class="px-3 py-3 text-center font-black {{ $i===0?'text-indigo-600':'text-gray-400' }}">
                                    {{ number_format($item['skor']*100,2) }}%
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>

        {{-- RIGHT (1/3) --}}
        <div class="col-span-1 flex flex-col gap-4">

            {{-- Bobot Tahap 2 --}}
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-50">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Bobot Kriteria (Tahap 2)</span>
                </div>
                <div class="px-5 py-4 flex flex-col gap-3">
                    @foreach($kriteria as $k)
                    @php $kode = is_array($k)?$k['kode']:$k->kode; $nama = is_array($k)?$k['nama']:$k->nama; $w = ($bobot[$kode] ?? 0)*100; @endphp
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-xs text-gray-600">{{ $nama }}</span>
                            <span class="text-xs font-bold text-indigo-600">{{ number_format($w,1) }}%</span>
                        </div>
                        <div class="h-1 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-400 rounded-full" style="--w: {{ $w }}%; width: var(--w);"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Tahap 1 info --}}
            <div class="rounded-2xl overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 40%, #1d4ed8 100%)">
                <div class="px-5 py-4">
                    <div class="text-[10px] font-bold text-blue-300 uppercase tracking-widest mb-3">Hasil Tahap 1</div>
                    @if($skipped)
                        <div class="flex items-center gap-2 bg-white/10 rounded-xl px-3 py-2">
                            <span class="text-lg">↷</span>
                            <div>
                                <div class="text-xs font-bold text-white">{{ $seri->nama }}</div>
                                <div class="text-[10px] text-blue-300">Dipilih manual (Tahap 1 dilewati)</div>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-2 bg-white/10 rounded-xl px-3 py-2">
                            <span class="text-lg">🏅</span>
                            <div>
                                <div class="text-xs font-bold text-white">{{ $winner['nama'] }}</div>
                                <div class="text-[10px] text-blue-300">Skor: {{ number_format(($winner['skor'] ?? 0)*100,2) }}%</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Kembali --}}
            @if(!isset($fromRiwayat))
            <a href="{{ route('riwayat.index') }}"
               class="flex items-center justify-center gap-2 border border-gray-200 text-gray-600 text-sm font-medium py-2.5 rounded-xl hover:bg-gray-50 transition">
                📋 Lihat Riwayat
            </a>
            @endif

        </div>
    </div>
</div>
@endsection