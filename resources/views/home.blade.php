@extends('layouts.app')
@section('title', 'BimmerGuide — SPK Pemilihan BMW')

@section('content')

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<section class="relative overflow-hidden bg-white">
    {{-- subtle grid bg --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#f0f0f0_1px,transparent_1px),linear-gradient(to_bottom,#f0f0f0_1px,transparent_1px)] bg-[size:40px_40px] opacity-60 pointer-events-none"></div>

    <div class="relative max-w-6xl mx-auto px-8 pt-20 pb-16 grid grid-cols-2 gap-12 items-center">
        {{-- LEFT --}}
        <div>
            <span class="inline-flex items-center gap-1.5 text-xs font-semibold tracking-widest text-indigo-700 bg-indigo-50 border border-indigo-100 px-3 py-1.5 rounded-full mb-6 uppercase">
                ◇ Metode SMART — Simple Multi-Attribute Rating Technique
            </span>

            <h1 class="text-[3.25rem] font-black leading-[1.08] tracking-tight text-gray-900 mb-5">
                Sistem<br>
                Pendukung<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">Keputusan</span><br>
                Pemilihan BMW
            </h1>

            <p class="text-sm text-gray-500 leading-relaxed mb-8 max-w-sm">
                Temukan BMW yang sempurna untuk Anda. Analisis multi-kriteria berbasis metode SMART membantu Anda memilih model terbaik sesuai kebutuhan nyata.
            </p>

            <div class="flex items-center gap-3">
                <a href="{{ route('spk.tahap1') }}"
                   class="inline-flex items-center gap-2 bg-indigo-600 text-white text-sm font-semibold px-6 py-3 rounded-xl hover:bg-indigo-700 active:scale-95 transition-all">
                    → Cari sekarang
                </a>
                <a href="#metode"
                   class="inline-flex items-center gap-2 border border-gray-200 text-gray-700 text-sm font-medium px-6 py-3 rounded-xl hover:bg-gray-50 transition">
                    ▷ Pelajari Metode
                </a>
            </div>
        </div>

        
        <div class="relative flex items-center justify-center">
            <div class="absolute -inset-4 bg-gradient-to-br from-indigo-50 to-blue-50 rounded-3xl"></div>
            <img src="{{ asset('images/ilusi_bmw.png') }}"
                 alt="Ilustrasi BMW"
                    class="relative w-full h-auto rounded-2xl shadow-lg object-cover">
            </div>
    </div>
</section>


<div class="border-y border-gray-100 bg-white">
    <div class="max-w-6xl mx-auto px-8 py-5 grid grid-cols-4 divide-x divide-gray-100">
        <div class="text-center px-6">
            <div class="text-2xl font-black text-gray-900">{{ $stats['total_model'] }}</div>
            <div class="text-xs text-gray-400 mt-0.5">Model BMW tersedia</div>
        </div>
        <div class="text-center px-6">
            <div class="text-2xl font-black text-gray-900">{{ $stats['total_kriteria'] }}</div>
            <div class="text-xs text-gray-400 mt-0.5">Kriteria Penilaian</div>
        </div>
        <div class="text-center px-6">
            <div class="text-2xl font-black text-indigo-600">Smart</div>
            <div class="text-xs text-gray-400 mt-0.5">Metode Analisis</div>
        </div>
        <div class="text-center px-6">
            <div class="text-2xl font-black text-indigo-600">100%</div>
            <div class="text-xs text-gray-400 mt-0.5">Hasil Rekomendasi</div>
        </div>
    </div>
</div>


<section class="bg-white">
    <div class="max-w-6xl mx-auto px-8 py-20 grid grid-cols-2 gap-16 items-center">
        {{-- LEFT --}}
        <div>
            <p class="text-xs font-bold text-indigo-600 uppercase tracking-widest mb-2">Kenapa Butuh SPK?</p>
            <h2 class="text-3xl font-black text-gray-900 mb-3">
                Bingung Memilih<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">BMW Pertama</span> Anda?
            </h2>
            <p class="text-sm text-gray-500 leading-relaxed mb-6">
                Memilih model BMW yang tepat bisa membingungkan. Setiap model punya keunggulan berbeda — dari performa, efisiensi, hingga gaya hidup.
                Sistem kami membantu Anda membuat keputusan berdasarkan data, bukan tebakan.
            </p>
            <a href="{{ route('spk.tahap1') }}"
               class="inline-flex items-center gap-2 bg-indigo-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-indigo-700 transition">
                ✦ Mulai analisis
            </a>
        </div>

        {{-- RIGHT — feature list --}}
        <div class="flex flex-col gap-2.5">
            @foreach ([
                ['icon'=>'💰', 'bg'=>'bg-yellow-50',  'border'=>'border-yellow-100', 'title'=>'Harga & Budget',          'sub'=>'Apakah sesuai dengan anggaran Anda?'],
                ['icon'=>'⚡', 'bg'=>'bg-blue-50',    'border'=>'border-blue-100',   'title'=>'Performa & Kecepatan',     'sub'=>'Seberapa bertenaga yang Anda butuhkan?'],
                ['icon'=>'🔧', 'bg'=>'bg-green-50',   'border'=>'border-green-100',  'title'=>'Konsumsi Bahan Bakar',     'sub'=>'Efisiensi untuk penggunaan sehari-hari'],
                ['icon'=>'✨', 'bg'=>'bg-pink-50',    'border'=>'border-pink-100',   'title'=>'Desain & Estetika',        'sub'=>'Tampilan yang mencerminkan gaya Anda'],
                ['icon'=>'🛋️','bg'=>'bg-purple-50',  'border'=>'border-purple-100', 'title'=>'Kenyamanan',               'sub'=>'Interior mewah atau sporty?'],
            ] as $item)
            <div class="flex items-center gap-4 border {{ $item['border'] }} rounded-2xl px-4 py-3 bg-white hover:shadow-sm transition">
                <div class="w-9 h-9 {{ $item['bg'] }} rounded-xl flex items-center justify-center text-base flex-shrink-0">
                    {{ $item['icon'] }}
                </div>
                <div>
                    <div class="text-sm font-semibold text-gray-900">{{ $item['title'] }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">{{ $item['sub'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<div class="border-t border-gray-100 mx-8"></div>


<section class="bg-white" id="metode">
    <div class="max-w-6xl mx-auto px-8 py-20">
        <p class="text-xs font-bold text-indigo-600 uppercase tracking-widest mb-2">Keunggulan</p>
        <h2 class="text-3xl font-black text-gray-900 mb-10">
            Fitur <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">Unggulan</span> Aplikasi
        </h2>
        <div class="grid grid-cols-3 gap-4">
            @foreach ([
                ['icon'=>'≡', 'title'=>'Penilaian Multi-Kriteria',   'sub'=>'Evaluasi setiap model BMW berdasarkan 5 kriteria utama dengan bobot yang dapat disesuaikan sesuai prioritas Anda.'],
                ['icon'=>'∿', 'title'=>'Metode SMART',                'sub'=>'Simple Multi-Attribute Rating Technique — metode ilmiah yang terbukti akurat untuk pengambilan keputusan multi-atribut.'],
                ['icon'=>'🏆','title'=>'Rekomendasi Terbaik',         'sub'=>'Dapatkan hasil analisis lengkap dengan peringkat dan skor untuk setiap alternatif BMW yang tersedia.'],
                ['icon'=>'↕', 'title'=>'Dua Tahap Seleksi',          'sub'=>'Pilih seri berdasarkan gaya hidup, lalu tentukan kode bodi spesifik berdasarkan kriteria teknis & finansial.'],
                ['icon'=>'◎', 'title'=>'Data dari Ahli',              'sub'=>'Nilai alternatif didasarkan pada data nyata dari wawancara mekanik BMW dan riset marketplace.'],
                ['icon'=>'◈', 'title'=>'Transparan & Akurat',         'sub'=>'Breakdown perhitungan SMART ditampilkan lengkap — utility tiap kriteria, bobot, dan skor akhir.'],
            ] as $feat)
            <div class="border border-gray-100 rounded-2xl p-5 bg-white hover:border-indigo-100 hover:shadow-sm transition group">
                <div class="text-2xl mb-3 text-gray-400 group-hover:text-indigo-500 transition font-mono">{{ $feat['icon'] }}</div>
                <div class="text-sm font-bold text-gray-900 mb-1.5">{{ $feat['title'] }}</div>
                <div class="text-xs text-gray-500 leading-relaxed">{{ $feat['sub'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<div class="border-t border-gray-100 mx-8"></div>


<section class="bg-white">
    <div class="max-w-6xl mx-auto px-8 py-20">
        <div class="text-center max-w-xl mx-auto mb-12">
            <p class="text-xs font-bold text-indigo-600 uppercase tracking-widest mb-2">Cara Kerja</p>
            <h2 class="text-3xl font-black text-gray-900 mb-3">
                Tiga Langkah <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">Mudah</span>
            </h2>
            <p class="text-sm text-gray-500 leading-relaxed">Proses sederhana untuk rekomendasi yang akurat dan transparan.</p>
        </div>

        <div class="grid grid-cols-3 gap-10 relative">
            {{-- connector line --}}
            <div class="absolute top-5 left-[calc(16.66%+1rem)] right-[calc(16.66%+1rem)] h-px bg-gray-100 hidden md:block"></div>

            @foreach ([
                ['num'=>'01', 'title'=>'Pilih Model BMW',         'sub'=>'Evaluasi seri BMW yang ingin Anda pertimbangkan dari daftar yang tersedia.'],
                ['num'=>'02', 'title'=>'Tentukan Bobot Kriteria', 'sub'=>'Atur tingkat kepentingan setiap kriteria. Sistem normalisasi bobot otomatis (Wi = wi / Σwi).'],
                ['num'=>'03', 'title'=>'Lihat Rekomendasi',       'sub'=>'Sistem menghitung peringkat BMW terbaik lengkap dengan breakdown perhitungan SMART.'],
            ] as $step)
            <div class="text-center relative">
                <div class="w-10 h-10 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 text-xs font-bold flex items-center justify-center mx-auto mb-4 relative z-10">
                    {{ $step['num'] }}
                </div>
                <div class="text-sm font-bold text-gray-900 mb-2">{{ $step['title'] }}</div>
                <div class="text-xs text-gray-500 leading-relaxed">{{ $step['sub'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>


<section class="max-w-6xl mx-auto px-8 pb-20">
    <div class="bg-gradient-to-r from-indigo-600 to-blue-600 rounded-2xl px-12 py-12 flex items-center justify-between gap-8">
        <div>
            <h2 class="text-2xl font-black text-white mb-2">Siap menemukan BMW pertama Anda?</h2>
            <p class="text-sm text-indigo-200 leading-relaxed">Mulai konsultasi sekarang dan dapatkan rekomendasi yang tepat berdasarkan kebutuhan Anda.</p>
        </div>
        <a href="{{ route('spk.tahap1') }}"
           class="flex-shrink-0 bg-white text-indigo-700 text-sm font-bold px-8 py-3.5 rounded-xl hover:bg-indigo-50 active:scale-95 transition-all whitespace-nowrap">
            Mulai analisis sekarang →
        </a>
    </div>
</section>

@endsection