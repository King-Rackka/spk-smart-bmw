@extends('layouts.app')
@section('title', 'BimmerGuide')

@section('content')

{{-- Hero --}}
<section class="max-w-6xl mx-auto px-8 pt-20 pb-16 grid grid-cols-2 gap-16 items-center">
    <div>
        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-indigo-800 bg-indigo-50 px-3 py-1.5 rounded-lg mb-6">
            Metode SMART
        </span>
        <h1 class="text-5xl font-medium leading-tight text-gray-900 mb-5">
            Sistem Pendukung<br>Keputusan Pemilihan<br><span class="text-indigo-600">BMW</span>
        </h1>
        <p class="text-base text-gray-500 leading-relaxed mb-8">
            Temukan BMW yang sempurna untuk Anda. Aplikasi ini membantu menganalisis dan merekomendasikan model BMW terbaik berdasarkan kriteria pilihan Anda menggunakan metode SMART.
        </p>
        <div class="flex gap-3">
            <a href="{{ route('spk.tahap1') }}"
               class="bg-indigo-600 text-white text-sm font-medium px-6 py-3 rounded-xl hover:bg-indigo-700 transition">
                Cari sekarang →
            </a>
            <a href="#metode"
               class="border border-gray-200 text-gray-700 text-sm font-medium px-6 py-3 rounded-xl hover:bg-gray-50 transition">
                Pelajari metode
            </a>
        </div>
    </div>

    {{-- Stats grid --}}
    <div class="grid grid-cols-2 gap-3">
        <div class="bg-gray-100 rounded-2xl p-5 ">
            <div class="text-center">
                <div class="text-3xl font-medium text-indigo-600">{{ $stats['total_model'] }}</div>
                <div class="text-xs text-gray-500 mt-1">Model BMW tersedia</div>
            </div>
        </div>
        <div class="bg-gray-100 rounded-2xl p-5">
            <div class="text-center">
                <div class="text-3xl font-medium text-indigo-600">{{ $stats['total_kriteria'] }}</div>
                <div class="text-xs text-gray-500 mt-1">Kriteria penilaian</div>
            </div>
        </div>
        <div class="col-span-2 bg-gray-100 rounded-2xl p-5 flex justify-around items-center">
            <div class="text-center">
                <div class="text-2xl font-medium text-indigo-600">SMART</div>
                <div class="text-xs text-gray-500 mt-1">Metode analisis</div>
            </div>
            <div class="w-px h-9 bg-gray-200"></div>
            <div class="text-center">
                <div class="text-2xl font-medium text-emerald-600">2 tahap</div>
                <div class="text-xs text-gray-500 mt-1">Proses seleksi</div>
            </div>
            <div class="w-px h-9 bg-gray-200"></div>
            <div class="text-center">
                <div class="text-2xl font-medium text-indigo-600">100%</div>
                <div class="text-xs text-gray-500 mt-1">Hasil rekomendasi</div>
            </div>
        </div>
    </div>
</section>

<div class="border-t border-gray-100 mx-8"></div>

{{-- Kenapa butuh SPK --}}
<section class="max-w-6xl mx-auto px-8 py-16">
    <div class="grid grid-cols-2 gap-16 items-start">
        <div>
            <p class="text-xs font-medium text-indigo-600 uppercase tracking-widest mb-2">Kenapa butuh SPK?</p>
            <h2 class="text-3xl font-medium text-gray-900 mb-4">Bingung memilih BMW pertama Anda?</h2>
            <p class="text-sm text-gray-500 leading-relaxed">
                Memilih model BMW yang tepat bisa membingungkan. Setiap model punya keunggulan berbeda — dari performa, efisiensi, hingga gaya hidup. Sistem kami membantu Anda membuat keputusan berdasarkan data, bukan tebakan.
            </p>
        </div>
        <div class="flex flex-col gap-3">
            @foreach ([
                ['icon'=>'💰', 'bg'=>'bg-indigo-50',  'title'=>'Harga & budget',          'sub'=>'Apakah sesuai dengan anggaran Anda?'],
                ['icon'=>'⚡', 'bg'=>'bg-emerald-50',  'title'=>'Performa & kecepatan',     'sub'=>'Seberapa bertenaga yang Anda butuhkan?'],
                ['icon'=>'🔧', 'bg'=>'bg-amber-50',    'title'=>'Biaya perawatan',           'sub'=>'Efisiensi untuk penggunaan sehari-hari'],
                ['icon'=>'🛋️', 'bg'=>'bg-blue-50',    'title'=>'Kenyamanan',                'sub'=>'Interior mewah atau sporty?'],
                ['icon'=>'✨', 'bg'=>'bg-rose-50',     'title'=>'Desain & estetika',         'sub'=>'Tampilan yang mencerminkan gaya Anda'],
            ] as $item)
            <div class="flex items-center gap-4 border border-gray-100 rounded-2xl p-4 bg-white">
                <div class="w-10 h-10 {{ $item['bg'] }} rounded-xl flex items-center justify-center text-lg flex-shrink-0">
                    {{ $item['icon'] }}
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-900">{{ $item['title'] }}</div>
                    <div class="text-xs text-gray-500 mt-0.5">{{ $item['sub'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<div class="border-t border-gray-100 mx-8"></div>

{{-- Fitur unggulan --}}
<section class="max-w-6xl mx-auto px-8 py-16" id="metode">
    <p class="text-xs font-medium text-indigo-600 uppercase tracking-widest mb-2">Keunggulan</p>
    <h2 class="text-3xl font-medium text-gray-900 mb-8">Fitur unggulan aplikasi</h2>
    <div class="grid grid-cols-3 gap-4">
        @foreach ([
            ['title'=>'Penilaian multi-kriteria',  'sub'=>'Nilai setiap model berdasarkan kriteria utama dengan bobot yang dapat disesuaikan sesuai preferensi Anda.'],
            ['title'=>'Metode SMART',               'sub'=>'Simple Multi-Attribute Rating Technique dengan normalisasi utility benefit & cost yang terstandar secara akademik.'],
            ['title'=>'Rekomendasi terbaik',        'sub'=>'Hasil analisis lengkap dengan peringkat dan skor untuk setiap alternatif BMW yang tersedia.'],
            ['title'=>'Dua tahap seleksi',          'sub'=>'Pilih seri berdasarkan gaya hidup, lalu tentukan kode bodi spesifik berdasarkan kriteria teknis & finansial.'],
            ['title'=>'Data dari ahli',             'sub'=>'Nilai alternatif didasarkan pada data nyata dari wawancara mekanik BMW dan riset marketplace.'],
            ['title'=>'Transparan & akurat',        'sub'=>'Breakdown perhitungan SMART ditampilkan lengkap — utility tiap kriteria, bobot, dan skor akhir.'],
        ] as $feat)
        <div class="border border-gray-100 rounded-2xl p-5 bg-white">
            <div class="text-sm font-medium text-gray-900 mb-2">{{ $feat['title'] }}</div>
            <div class="text-xs text-gray-500 leading-relaxed">{{ $feat['sub'] }}</div>
        </div>
        @endforeach
    </div>
</section>

<div class="border-t border-gray-100 mx-8"></div>

{{-- Cara kerja --}}
<section class="max-w-6xl mx-auto px-8 py-16">
    <div class="text-center max-w-xl mx-auto mb-10">
        <p class="text-xs font-medium text-indigo-600 uppercase tracking-widest mb-2">Cara kerja</p>
        <h2 class="text-3xl font-medium text-gray-900 mb-3">Tiga langkah mudah</h2>
        <p class="text-sm text-gray-500 leading-relaxed">Proses sederhana untuk rekomendasi yang akurat dan transparan.</p>
    </div>
    <div class="grid grid-cols-3 gap-8">
        @foreach ([
            ['num'=>'01', 'title'=>'Pilih model BMW',         'sub'=>'Evaluasi seri BMW yang ingin Anda pertimbangkan dari daftar yang tersedia: Seri 3, Seri 5, atau Seri X.'],
            ['num'=>'02', 'title'=>'Tentukan bobot kriteria', 'sub'=>'Atur tingkat kepentingan setiap kriteria sesuai kebutuhan Anda. Sistem normalisasi bobot otomatis (Wi = wi / Σwi).'],
            ['num'=>'03', 'title'=>'Lihat rekomendasi',       'sub'=>'Sistem menghitung peringkat BMW terbaik lengkap dengan breakdown perhitungan SMART secara transparan.'],
        ] as $step)
        <div class="text-center px-4">
            <div class="w-11 h-11 rounded-full bg-indigo-50 text-indigo-600 text-sm font-medium flex items-center justify-center mx-auto mb-4">
                {{ $step['num'] }}
            </div>
            <div class="text-sm font-medium text-gray-900 mb-2">{{ $step['title'] }}</div>
            <div class="text-xs text-gray-500 leading-relaxed">{{ $step['sub'] }}</div>
        </div>
        @endforeach
    </div>
</section>

{{-- CTA --}}
<section class="max-w-6xl mx-auto px-8 pb-16">
    <div class="bg-indigo-600 rounded-2xl px-12 py-12 flex items-center justify-between gap-8">
        <div>
            <h2 class="text-2xl font-medium text-white mb-2">Siap menemukan BMW pertama Anda?</h2>
            <p class="text-sm text-indigo-200 leading-relaxed">Mulai konsultasi sekarang dan dapatkan rekomendasi yang tepat berdasarkan kebutuhan Anda.</p>
        </div>
        <a href="{{ route('spk.tahap1') }}"
           class="flex-shrink-0 bg-white text-indigo-700 text-sm font-medium px-8 py-3.5 rounded-xl hover:bg-indigo-50 transition whitespace-nowrap">
            Mulai analisis sekarang →
        </a>
    </div>
</section>

@endsection
