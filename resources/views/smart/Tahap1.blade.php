@extends('layouts.app')
@section('title', 'Tahap 1 — Pilih Seri BMW')

@section('content')
<div class="max-w-6xl mx-auto px-8 py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-gray-600 transition">Home</a>
        <span>/</span>
        <span class="text-indigo-600 font-medium">Tahap 1</span>
    </nav>

    <h1 class="text-3xl font-black text-gray-900 mb-1">
        Tahap 1 — <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">Pemilihan Seri BMW</span>
    </h1>
    <p class="text-sm text-gray-500 mb-8 max-w-xl">
        Pilih seri BMW yang ingin dianalisis, kemudian tentukan bobot untuk setiap kriteria penilaian sesuai prioritas Anda.
    </p>
    

    {{-- Step Indicator --}}
    <div class="flex items-center gap-3 mb-8">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center">1</div>
            <span class="text-sm font-semibold text-gray-900">Pilih Seri & Bobot</span>
        </div>
        <div class="flex-1 max-w-[120px] h-px bg-gray-200"></div>
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full border-2 border-gray-200 text-gray-400 text-xs font-bold flex items-center justify-center">2</div>
            <span class="text-sm text-gray-400">Pilih Mobil & Bobot</span>
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-3 gap-6">

        {{-- LEFT — Slider form --}}
        <div class="col-span-2">
            <form action="{{ route('spk.tahap1.hitung') }}" method="POST" id="form-tahap1">
                @csrf

                <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50">
                        <div class="text-sm font-bold text-gray-900">Bobot Kriteria Penilaian</div>
                        <div class="text-xs text-gray-400 mt-0.5">
                            Tentukan tingkat kepentingan (0–100) setiap kriteria.
                            <span class="text-amber-500 font-medium">Total bobot harus tepat 100.</span>
                        </div>
                    </div>

                    {{-- Sliders --}}
                    <div class="divide-y divide-gray-50">
                        @foreach ($kriteria as $k)
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-semibold text-gray-900">{{ $k->nama }}</span>
                                    @if($k->tipe === 'cost')
                                        <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">Cost</span>
                                    @else
                                        <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Benefit</span>
                                    @endif
                                </div>
                                <span id="val-{{ $k->kode }}" class="text-lg font-black text-indigo-600 min-w-[2.5rem] text-right">40</span>
                            </div>

                            {{-- Pertanyaan --}}
                            <p class="text-xs text-gray-400 mb-3 leading-relaxed">{{ $k->pertanyaan }}</p>

                            <input
                                type="range" min="0" max="100" value="40" step="1"
                                name="bobot[{{ $k->kode }}]"
                                id="slider-{{ $k->kode }}"
                                data-kode="{{ $k->kode }}"
                                class="w-full h-1.5 bg-gray-200 rounded-full appearance-none cursor-pointer slider-input
                                       [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:h-4
                                       [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-indigo-600
                                       [&::-webkit-slider-thumb]:cursor-pointer [&::-webkit-slider-thumb]:shadow-sm
                                       [&::-moz-range-thumb]:w-4 [&::-moz-range-thumb]:h-4 [&::-moz-range-thumb]:rounded-full
                                       [&::-moz-range-thumb]:bg-indigo-600 [&::-moz-range-thumb]:border-0"
                                oninput="syncSlider(this)"
                            >

                            <div class="flex justify-between text-[10px] text-gray-300 mt-1 px-0.5">
                                <span>0</span><span>25</span><span>50</span><span>75</span><span>100</span>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 border-t border-gray-50 flex items-center justify-between">
                        {{-- Total bobot indicator --}}
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-400">Total Bobot</span>
                            <span id="total-bobot" class="text-sm font-black text-indigo-600">200</span>
                            <span id="bobot-warning" class="hidden text-[11px] font-medium text-red-500 bg-red-50 px-2 py-0.5 rounded-full">
                                Harus 100!
                            </span>
                            <span id="bobot-ok" class="hidden text-[11px] font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                                ✓ 
                            </span>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex items-center gap-3">
                            {{-- Skip ke Tahap 2 --}}
                            <button type="button" onclick="bukaModalSkip()"
                                    class="flex items-center gap-1.5 border border-gray-200 text-gray-500 text-sm font-medium px-4 py-2.5 rounded-xl hover:border-indigo-200 hover:text-indigo-600 transition">
                                Lewati ↷
                            </button>

                            {{-- Tombol Hitung Seri (tampil duluan) --}}
                            <button type="button" id="btn-hitung"
                                    onclick="hitungPreview()"
                                    class="flex items-center gap-1.5 bg-indigo-600 text-white text-sm font-semibold px-6 py-2.5 rounded-xl hover:bg-indigo-700 active:scale-95 transition-all disabled:opacity-40 disabled:cursor-not-allowed">
                                Hitung Seri →
                            </button>

                            {{-- Tombol Lanjut (tersembunyi, muncul setelah hitung) --}}
                            <button type="submit" id="btn-lanjut"
                                    class="hidden flex items-center gap-1.5 bg-indigo-600 text-white text-sm font-semibold px-6 py-2.5 rounded-xl hover:bg-indigo-700 active:scale-95 transition-all">
                                Lanjut Ke Tahap 2 →
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- RIGHT — Preview panel --}}
        <div class="col-span-1 flex flex-col gap-4">

            {{-- Pilihan Anda --}}
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-50">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pilihan Anda</span>
                </div>

                {{-- State: belum hitung --}}
                <div id="preview-empty" class="px-5 py-8 text-center">
                    <div class="text-3xl mb-2">🚗</div>
                    <div class="text-xs text-gray-400">Klik <strong>Hitung Seri</strong> untuk melihat<br>rekomendasi seri BMW</div>
                </div>

                {{-- State: setelah hitung --}}
                <div id="preview-result" class="hidden px-5 py-6 text-center">
                    <div class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full inline-block mb-3">#1 Rekomendasi</div>
                    <div id="preview-seri-nama" class="text-2xl font-black text-indigo-700 mb-1">—</div>
                    <div id="preview-seri-skor" class="text-xs text-gray-400"></div>
                    <div class="mt-3 pt-3 border-t border-gray-50">
                        <div class="text-[10px] text-gray-400 mb-2">Semua Seri</div>
                        <div id="preview-ranking" class="flex flex-col gap-1.5 text-left"></div>
                    </div>
                </div>
            </div>

            {{-- Bobot ternormalisasi --}}
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-50">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Bobot Ternormalisasi</span>
                    <div class="text-[10px] text-gray-300 mt-0.5">Wi = wi / Σwi</div>
                </div>
                <div class="px-5 py-4 flex flex-col gap-3">
                    @foreach ($kriteria as $k)
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs text-gray-600">{{ $k->nama }}</span>
                            <span id="norm-{{ $k->kode }}" class="text-xs font-bold text-indigo-600">—%</span>
                        </div>
                        <div class="h-1 bg-gray-100 rounded-full overflow-hidden">
                            <div id="bar-{{ $k->kode }}" class="h-full bg-indigo-400 rounded-full transition-all duration-300" style="width:0%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Info --}}

            <div class="rounded-2xl px-5 py-5" style="background: linear-gradient(135deg, #1e3a5f 0%, #1e40af 60%, #3b82f6 100%)">
                <div class="text-xs font-bold text-white mb-2">Tentang Tahap 1</div>
                <div class="text-xs text-blue-200 leading-relaxed">
                    Sistem SMART menghitung utility setiap seri BMW lalu memilih seri terbaik untuk dilanjutkan ke Tahap 2.
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Data seri untuk kalkulasi client-side --}}
{{-- ═══ MODAL SKIP ═══ --}}
<div id="modal-skip"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden"
     onclick="if(event.target===this) tutupModalSkip()">

    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/30 backdrop-blur-sm"></div>

    {{-- Panel --}}
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 z-10">
        <div class="mb-5">
            <h3 class="text-base font-black text-gray-900 mb-1">Lewati Tahap 1</h3>
            <p class="text-xs text-gray-400 leading-relaxed">
                Pilih seri BMW yang ingin langsung dianalisis di Tahap 2. Skor seri tidak dihitung.
            </p>
        </div>

        {{-- Seri options --}}
        <div class="flex flex-col gap-2 mb-5" id="seri-options">
            @foreach($seris as $s)
            <button type="button"
                    onclick="pilihSeriSkip(this.dataset.id)"
                    class="seri-option flex items-center justify-between px-4 py-3 border border-gray-100 rounded-xl text-left hover:border-indigo-200 hover:bg-indigo-50 transition group"
                    data-id="{{ $s['id'] }}">
                <span class="text-sm font-semibold text-gray-900 group-hover:text-indigo-700">{{ $s['nama'] }}</span>
                <span class="text-gray-300 group-hover:text-indigo-400">→</span>
            </button>
            @endforeach
        </div>

        <button onclick="tutupModalSkip()"
                class="w-full text-sm text-gray-400 hover:text-gray-600 py-2 transition">
            Batal
        </button>
    </div>
</div>

{{-- Form hidden untuk skip (POST ke hitungTahap1 dengan seri terpilih) --}}
<form id="form-skip" action="{{ route('spk.tahap1.skip') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="seri_id" id="skip-seri-id">
</form>

<script>
function bukaModalSkip() {
    document.getElementById('modal-skip').classList.remove('hidden');
}
function tutupModalSkip() {
    document.getElementById('modal-skip').classList.add('hidden');
}
function pilihSeriSkip(id) {
    document.getElementById('skip-seri-id').value = id;
    document.getElementById('form-skip').submit();
}
</script>

<script>
const SERIS_DATA = JSON.parse('{!! json_encode($seris) !!}');
const KRITERIA_DATA = JSON.parse('{!! json_encode($kriteria) !!}');

// ── Sync slider value display ───────────────────────────────────
function syncSlider(input) {
    const kode = input.dataset.kode;
    document.getElementById('val-' + kode).textContent = input.value;
    updateNormalized();
    resetPreview(); // reset preview kalau slider diubah setelah hitung
}

// ── Update bobot ternormalisasi (live) ──────────────────────────
function updateNormalized() {
    const sliders = document.querySelectorAll('.slider-input');
    let total = 0;
    const vals = {};

    sliders.forEach(s => {
        const kode = s.dataset.kode;
        const v = parseFloat(s.value) || 0;
        vals[kode] = v;
        total += v;
    });

    // Total indicator
    const totalEl = document.getElementById('total-bobot');
    const warnEl  = document.getElementById('bobot-warning');
    const okEl    = document.getElementById('bobot-ok');
    const btnHitung = document.getElementById('btn-hitung');

    totalEl.textContent = total;

    if (total === 100) {
        totalEl.classList.replace('text-indigo-600', 'text-emerald-600');
        warnEl.classList.add('hidden');
        okEl.classList.remove('hidden');
        btnHitung.disabled = false;
    } else {
        totalEl.classList.replace('text-emerald-600', 'text-indigo-600');
        warnEl.classList.remove('hidden');
        okEl.classList.add('hidden');
        btnHitung.disabled = true;
    }

    // Update bars
    Object.entries(vals).forEach(([kode, v]) => {
        const pct = total > 0 ? ((v / total) * 100).toFixed(1) : '0.0';
        const el  = document.getElementById('norm-' + kode);
        const bar = document.getElementById('bar-' + kode);
        if (el)  el.textContent  = pct + '%';
        if (bar) bar.style.width = pct + '%';
    });
}

// ── SMART preview (client-side) ─────────────────────────────────
function hitungPreview() {
    const sliders = document.querySelectorAll('.slider-input');
    let total = 0;
    const bobotRaw = {};

    sliders.forEach(s => {
        bobotRaw[s.dataset.kode] = parseFloat(s.value) || 0;
        total += bobotRaw[s.dataset.kode];
    });

    if (total !== 100) return; // guard

    // Normalisasi bobot
    const bobot = {};
    Object.entries(bobotRaw).forEach(([k, v]) => bobot[k] = v / total);

    // Cari min/max per kriteria
    const minVal = {}, maxVal = {};
    KRITERIA_DATA.forEach(kr => {
        const vals = SERIS_DATA.map(s => s.nilai[kr.kode] ?? 0);
        minVal[kr.kode] = Math.min(...vals);
        maxVal[kr.kode] = Math.max(...vals);
    });

    // Hitung utility & skor SMART
    const ranked = SERIS_DATA.map(seri => {
        let skor = 0;
        KRITERIA_DATA.forEach(kr => {
            const v    = seri.nilai[kr.kode] ?? 0;
            const min  = minVal[kr.kode];
            const max  = maxVal[kr.kode];
            let utility = (max === min) ? 1 : (v - min) / (max - min);
            if (kr.tipe === 'cost') utility = 1 - utility;
            skor += bobot[kr.kode] * utility;
        });
        return { ...seri, skor: parseFloat((skor * 100).toFixed(2)) };
    }).sort((a, b) => b.skor - a.skor);

    // Tampilkan hasil
    showPreview(ranked);
}

function showPreview(ranked) {
    document.getElementById('preview-empty').classList.add('hidden');
    document.getElementById('preview-result').classList.remove('hidden');

    const winner = ranked[0];
    document.getElementById('preview-seri-nama').textContent = winner.nama;
    document.getElementById('preview-seri-skor').textContent = 'Skor SMART: ' + winner.skor + '%';

    // Ranking list
    const rankEl = document.getElementById('preview-ranking');
    rankEl.innerHTML = ranked.map((s, i) => `
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-bold w-4 ${i===0?'text-indigo-600':'text-gray-400'}">#${i+1}</span>
                <span class="text-xs ${i===0?'font-semibold text-gray-900':'text-gray-500'}">${s.nama}</span>
            </div>
            <span class="text-[10px] font-bold ${i===0?'text-indigo-600':'text-gray-400'}">${s.skor}%</span>
        </div>
    `).join('');

    // Ganti tombol
    document.getElementById('btn-hitung').classList.add('hidden');
    document.getElementById('btn-lanjut').classList.remove('hidden');
}

function resetPreview() {
    document.getElementById('preview-empty').classList.remove('hidden');
    document.getElementById('preview-result').classList.add('hidden');
    document.getElementById('btn-hitung').classList.remove('hidden');
    document.getElementById('btn-lanjut').classList.add('hidden');
}

// Init
updateNormalized();
</script>
@endsection