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
        Tahap 1 — <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">Pemilihan Mobil BMW</span>
    </h1>
    <p class="text-sm text-gray-500 mb-8 max-w-xl">
        Pilih model BMW yang ingin dianalisis, kemudian tentukan bobot untuk setiap kriteria penilaian sesuai prioritas Anda.
    </p>

    {{-- Step Indicator --}}
    <div class="flex items-center gap-3 mb-8">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center">1</div>
            <span class="text-sm font-semibold text-gray-900">Pilih Model & Bobot</span>
        </div>
        <div class="flex-1 max-w-[120px] h-px bg-gray-200"></div>
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full border-2 border-gray-200 text-gray-400 text-xs font-bold flex items-center justify-center">2</div>
            <span class="text-sm text-gray-400">Analisis SMART</span>
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
            <div class="bg-indigo-50 border border-indigo-100 rounded-2xl px-5 py-4">
                <div class="text-xs font-bold text-indigo-700 mb-1">Tentang Tahap 1</div>
                <div class="text-xs text-indigo-500 leading-relaxed">
                    Sistem SMART menghitung utility setiap seri BMW lalu memilih seri terbaik untuk dilanjutkan ke Tahap 2.
                </div>
            </div>

        </div>
    </div>

    {{-- ═══ BREAKDOWN PERHITUNGAN (muncul setelah hitung) ═══ --}}
    <div id="section-breakdown" class="hidden mt-6 flex flex-col gap-4">

        {{-- Tabel Bobot Ternormalisasi --}}
        <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50">
                <div class="text-sm font-bold text-gray-900">📐 Langkah 1 — Normalisasi Bobot</div>
                <div class="text-xs text-gray-400 mt-0.5">Wi = wi / Σwi &nbsp;→&nbsp; Total normalisasi selalu = 1.0000</div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs" id="tbl-bobot-normal">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-5 py-3 text-left font-semibold text-gray-500">Kriteria</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-500">Tipe</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-500">Bobot Input (wi)</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-500">Σwi</th>
                            <th class="px-4 py-3 text-center font-semibold text-indigo-500">Bobot Normal (Wi)</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-400">Formula</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-bobot-normal" class="divide-y divide-gray-50"></tbody>
                </table>
            </div>
        </div>

        {{-- Tabel Nilai & Xmin Xmax --}}
        <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50">
                <div class="text-sm font-bold text-gray-900">📊 Langkah 2 — Matriks Nilai & Xmin/Xmax</div>
                <div class="text-xs text-gray-400 mt-0.5">Nilai tiap seri per kriteria, beserta nilai minimum dan maksimum</div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs" id="tbl-nilai">
                    <thead id="thead-nilai">
                        <tr class="bg-gray-50/50 border-b border-gray-100"></tr>
                    </thead>
                    <tbody id="tbody-nilai" class="divide-y divide-gray-50"></tbody>
                </table>
            </div>
        </div>

        {{-- Tabel Utility --}}
        <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50">
                <div class="text-sm font-bold text-gray-900">⚡ Langkah 3 — Perhitungan Utility</div>
                <div class="text-xs text-gray-400 mt-0.5">
                    Benefit: U = (X − Xmin) / (Xmax − Xmin) &nbsp;|&nbsp; Cost: U = (Xmax − X) / (Xmax − Xmin)
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead id="thead-utility">
                        <tr class="bg-gray-50/50 border-b border-gray-100"></tr>
                    </thead>
                    <tbody id="tbody-utility" class="divide-y divide-gray-50"></tbody>
                </table>
            </div>
        </div>

        {{-- Tabel Skor SMART --}}
        <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50">
                <div class="text-sm font-bold text-gray-900">🏆 Langkah 4 — Skor SMART & Ranking</div>
                <div class="text-xs text-gray-400 mt-0.5">S(Ai) = Σ [Wi × U(xij)] &nbsp;→&nbsp; diurutkan dari skor tertinggi</div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead id="thead-skor">
                        <tr class="bg-gray-50/50 border-b border-gray-100"></tr>
                    </thead>
                    <tbody id="tbody-skor" class="divide-y divide-gray-50"></tbody>
                </table>
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
        onclick="pilihSeriSkip(this)"
        class="seri-option flex items-center justify-between px-4 py-3 border border-gray-100 rounded-xl"
        data-id="{{ $s['id'] }}"
        data-nama="{{ $s['nama'] }}"
        data-slug="{{ $s['slug'] }}">
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
function pilihSeriSkip(id, nama, slug) {
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

    if (total !== 100) return;

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

    // Hitung utility & skor SMART (simpan utility per seri)
    const ranked = SERIS_DATA.map(seri => {
        let skor = 0;
        const utility = {};
        const skorPerK = {};
        KRITERIA_DATA.forEach(kr => {
            const v    = seri.nilai[kr.kode] ?? 0;
            const min  = minVal[kr.kode];
            const max  = maxVal[kr.kode];
            let u = (max === min) ? 0 : (kr.tipe === 'cost')
                ? (max - v) / (max - min)
                : (v - min)  / (max - min);
            u = parseFloat(u.toFixed(6));
            utility[kr.kode]  = u;
            skorPerK[kr.kode] = parseFloat((bobot[kr.kode] * u).toFixed(6));
            skor += bobot[kr.kode] * u;
        });
        return { ...seri, utility, skorPerK, skor: parseFloat((skor * 100).toFixed(4)) };
    }).sort((a, b) => b.skor - a.skor);

    showPreview(ranked, bobot, bobotRaw, total, minVal, maxVal);
}

function showPreview(ranked, bobot, bobotRaw, total, minVal, maxVal) {
    document.getElementById('preview-empty').classList.add('hidden');
    document.getElementById('preview-result').classList.remove('hidden');

    const winner = ranked[0];
    document.getElementById('preview-seri-nama').textContent = winner.nama;
    document.getElementById('preview-seri-skor').textContent = 'Skor SMART: ' + winner.skor + '%';

    const medals = ['🥇','🥈','🥉'];
    const rankEl = document.getElementById('preview-ranking');
    rankEl.innerHTML = ranked.map((s, i) => `
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-1.5">
                <span class="text-sm">${medals[i] ?? '#'+(i+1)}</span>
                <span class="text-xs ${i===0?'font-bold text-gray-900':'text-gray-500'}">${s.nama}</span>
            </div>
            <span class="text-[10px] font-bold ${i===0?'text-indigo-600':'text-gray-400'}">${s.skor}%</span>
        </div>
    `).join('');

    // Ganti tombol
    document.getElementById('btn-hitung').classList.add('hidden');
    document.getElementById('btn-lanjut').classList.remove('hidden');

    // ── Tampilkan breakdown perhitungan ──────────────────────────
    document.getElementById('section-breakdown').classList.remove('hidden');

    // 1. Tabel Bobot Normal
    const tbodyBobot = document.getElementById('tbody-bobot-normal');
    tbodyBobot.innerHTML = KRITERIA_DATA.map(kr => {
        const wi = bobotRaw[kr.kode];
        const Wi = bobot[kr.kode];
        return `<tr class="hover:bg-gray-50/50">
            <td class="px-5 py-2.5 font-semibold text-gray-900">${kr.nama}</td>
            <td class="px-4 py-2.5 text-center">
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full ${kr.tipe==='benefit'?'text-emerald-600 bg-emerald-50':'text-blue-600 bg-blue-50'}">${kr.tipe}</span>
            </td>
            <td class="px-4 py-2.5 text-center font-bold text-gray-700">${wi}</td>
            <td class="px-4 py-2.5 text-center text-gray-400">${total}</td>
            <td class="px-4 py-2.5 text-center font-bold text-indigo-600">${Wi.toFixed(4)}</td>
            <td class="px-4 py-2.5 text-center text-gray-300 font-mono text-[10px]">${wi}/${total} = ${Wi.toFixed(4)}</td>
        </tr>`;
    }).join('');

    // 2. Tabel Nilai + Xmin/Xmax
    const theadNilai = document.getElementById('thead-nilai').querySelector('tr') || document.getElementById('thead-nilai').insertRow();
    theadNilai.innerHTML = `<th class="px-5 py-3 text-left font-semibold text-gray-500">Seri BMW</th>`
        + KRITERIA_DATA.map(kr => `<th class="px-4 py-3 text-center font-semibold ${kr.tipe==='benefit'?'text-emerald-500':'text-blue-500'}">${kr.nama}<div class="text-[9px] font-normal opacity-60">${kr.tipe}</div></th>`).join('');
    document.getElementById('tbody-nilai').innerHTML =
        ranked.map(s => `<tr class="hover:bg-gray-50/50">
            <td class="px-5 py-2.5 font-semibold text-gray-900">${s.nama}</td>
            ${KRITERIA_DATA.map(kr => `<td class="px-4 py-2.5 text-center text-gray-600">${s.nilai[kr.kode]}</td>`).join('')}
        </tr>`).join('')
        + `<tr class="bg-red-50/50"><td class="px-5 py-2 font-bold text-red-600 text-[10px]">Xmin</td>${KRITERIA_DATA.map(kr=>`<td class="px-4 py-2 text-center font-bold text-red-500 text-[10px]">${minVal[kr.kode]}</td>`).join('')}</tr>`
        + `<tr class="bg-emerald-50/50"><td class="px-5 py-2 font-bold text-emerald-600 text-[10px]">Xmax</td>${KRITERIA_DATA.map(kr=>`<td class="px-4 py-2 text-center font-bold text-emerald-500 text-[10px]">${maxVal[kr.kode]}</td>`).join('')}</tr>`;

    // 3. Tabel Utility
    document.getElementById('thead-utility').querySelector('tr').innerHTML =
        `<th class="px-5 py-3 text-left font-semibold text-gray-500">Seri BMW</th>`
        + KRITERIA_DATA.map(kr => `<th class="px-4 py-3 text-center font-semibold ${kr.tipe==='benefit'?'text-emerald-500':'text-blue-500'}">${kr.nama}</th>`).join('')
        + `<th class="px-4 py-3 text-center font-semibold text-indigo-500">Skor (S)</th>`;
    document.getElementById('tbody-utility').innerHTML = ranked.map((s,i) => `
        <tr class="${i===0?'bg-indigo-50/30':''} hover:bg-gray-50/50">
            <td class="px-5 py-2.5 font-semibold ${i===0?'text-indigo-700':'text-gray-900'}">${medals[i]??'#'+(i+1)} ${s.nama}</td>
            ${KRITERIA_DATA.map(kr => {
                const u = s.utility[kr.kode];
                const cls = u>=0.75?'bg-emerald-50 text-emerald-600':u>=0.5?'bg-blue-50 text-blue-600':u>=0.25?'bg-amber-50 text-amber-600':'bg-red-50 text-red-500';
                return `<td class="px-4 py-2.5 text-center"><span class="inline-flex items-center justify-center w-14 h-6 rounded text-[10px] font-bold ${cls}">${u.toFixed(4)}</span></td>`;
            }).join('')}
            <td class="px-4 py-2.5 text-center font-black ${i===0?'text-indigo-600':'text-gray-400'}">${s.skor}%</td>
        </tr>
    `).join('');

    // 4. Tabel Skor detail (Wi × Ui per kriteria)
    document.getElementById('thead-skor').querySelector('tr').innerHTML =
        `<th class="px-5 py-3 text-left font-semibold text-gray-500">Seri BMW</th>`
        + KRITERIA_DATA.map(kr => `<th class="px-4 py-3 text-center font-semibold text-gray-400 text-[10px]">Wi×Ui<br><span class="text-[9px]">${kr.nama.substring(0,12)}</span></th>`).join('')
        + `<th class="px-4 py-3 text-center font-semibold text-indigo-500">Skor Total</th><th class="px-4 py-3 text-center font-semibold text-gray-400">Rank</th>`;
    document.getElementById('tbody-skor').innerHTML = ranked.map((s,i) => `
        <tr class="${i===0?'bg-indigo-50/30':''} hover:bg-gray-50/50">
            <td class="px-5 py-2.5 font-semibold ${i===0?'text-indigo-700':'text-gray-900'}">${s.nama}</td>
            ${KRITERIA_DATA.map(kr => `<td class="px-4 py-2.5 text-center text-[10px] text-gray-500">${s.skorPerK[kr.kode].toFixed(4)}</td>`).join('')}
            <td class="px-4 py-2.5 text-center font-black text-lg ${i===0?'text-indigo-600':'text-gray-400'}">${s.skor}%</td>
            <td class="px-4 py-2.5 text-center font-bold text-gray-500">${i+1}</td>
        </tr>
    `).join('');

    // Scroll ke breakdown
    document.getElementById('section-breakdown').scrollIntoView({behavior:'smooth', block:'start'});
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