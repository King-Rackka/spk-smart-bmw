@extends('layouts.app')
@section('title', 'Tahap 2 — Pemilihan Mobil BMW')

@section('content')
<div class="max-w-6xl mx-auto px-8 py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-gray-600 transition">Home</a>
        <span>/</span>
        <a href="{{ route('spk.tahap1') }}" class="hover:text-gray-600 transition">Tahap 1</a>
        <span>/</span>
        <span class="text-indigo-600 font-medium">Tahap 2</span>
    </nav>

    <h1 class="text-3xl font-black text-gray-900 mb-1">
        Tahap 2 — <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">SMART</span><br>
        Pemilihan Mobil BMW
    </h1>
    <p class="text-sm text-gray-500 mb-8">Pilih BMW → Atur Bobot → Hitung SMART → Lihat Ranking.</p>

    {{-- Step Indicator --}}
    <div class="flex items-center gap-3 mb-10">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-emerald-500 text-white text-xs font-bold flex items-center justify-center">✓</div>
            <span class="text-sm font-semibold text-gray-600">Pilih Model & Bobot</span>
        </div>
        <div class="flex-1 max-w-[120px] h-px bg-indigo-200"></div>
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center">2</div>
            <span class="text-sm font-semibold text-indigo-600">Analisis SMART</span>
        </div>
    </div>

    <form action="{{ route('spk.tahap2.hitung') }}" method="POST" id="form-tahap2">
        @csrf

        {{-- ══ PILIH MOBIL ══════════════════════════════════════ --}}
        <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">🚗</span>
                        <div>
                            <div class="text-sm font-bold text-gray-900">Pilih Mobil BMW</div>
                            <div class="text-xs text-gray-400 mt-0.5">
                                Dari <span class="font-semibold text-indigo-600">{{ $seri->nama }}</span>
                                — pilih minimal <span class="font-semibold text-amber-500">2 model</span> untuk dibandingkan
                            </div>
                        </div>
                    </div>
                    {{-- Pilih semua --}}
                    <button type="button" onclick="togglePilihSemua()"
                            id="btn-semua"
                            class="text-xs font-semibold text-indigo-600 border border-indigo-200 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition">
                        ☑ Pilih Semua
                    </button>
                </div>

                {{-- Status bar --}}
                <div class="mt-3 flex items-center gap-3">
                    <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div id="bar-pilih" class="h-full bg-indigo-500 rounded-full transition-all duration-300" style="width:0%"></div>
                    </div>
                    <span class="text-xs text-gray-400">
                        <span id="counter-pilih" class="font-bold text-indigo-600">0</span>/{{ count($kodeBodis) }} dipilih
                    </span>
                </div>

                {{-- Warning --}}
                <div id="pesan-pilih" class="hidden mt-2 bg-amber-50 border border-amber-100 text-amber-700 text-xs px-3 py-2 rounded-lg flex items-center gap-1.5">
                    ⚠ Pilih minimal 2 model BMW untuk melanjutkan.
                </div>
            </div>

            <div class="p-5">
                <div class="grid grid-cols-4 gap-3">
                    @foreach($kodeBodis as $mobil)
                    <label class="relative cursor-pointer group select-none" data-id="{{ $mobil->id }}">
                        <input type="checkbox"
                               name="selected_mobil[]"
                               value="{{ $mobil->id }}"
                               class="peer hidden mobil-checkbox"
                               onchange="onMobilChange()">

                        <div class="border-2 border-gray-100 rounded-xl overflow-hidden transition-all duration-150
                                    peer-checked:border-indigo-500 peer-checked:shadow-md peer-checked:shadow-indigo-100
                                    group-hover:border-gray-300 group-hover:shadow-sm">

                            {{-- Gambar fixed height --}}
                            <div class="h-28 bg-gray-50 overflow-hidden">
                                @if($mobil->gambar)
                                    <img src="{{ asset('images/mobil/' . $mobil->gambar) }}"
                                         alt="{{ $mobil->nama_lengkap }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-3xl text-gray-200">🚘</div>
                                @endif
                            </div>

                            {{-- Info fixed structure --}}
                            <div class="p-3">
                                <div class="text-xs font-black text-gray-900 leading-tight mb-1 line-clamp-1">{{ $mobil->nama_lengkap }}</div>
                                <div class="text-[10px] text-gray-400 mb-2">{{ $mobil->tahun }}</div>

                                <div class="space-y-0.5 mb-3">
                                    <div class="flex items-center gap-1 text-[10px] text-gray-500">
                                        <span class="text-gray-300">⚙</span>{{ ucfirst($mobil->transmisi) }}
                                    </div>
                                    <div class="flex items-center gap-1 text-[10px] text-gray-500">
                                        <span class="text-gray-300">🚗</span>{{ ucfirst(str_replace('_',' ',$mobil->tipe_bodi)) }}
                                    </div>
                                    <div class="flex items-center gap-1 text-[10px] text-gray-500">
                                        <span class="text-gray-300">⛽</span>{{ ucfirst($mobil->bahan_bakar) }}
                                    </div>
                                </div>

                                {{-- Checkbox visual --}}
                                <div class="flex items-center justify-center pt-2 border-t border-gray-50">
                                    <div class="w-5 h-5 rounded border-2 border-gray-200 flex items-center justify-center transition-all
                                                check-box-visual">
                                        <svg class="w-3 h-3 text-white hidden check-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ══ BOBOT + PANEL KANAN ════════════════════════════════ --}}
        <div class="grid grid-cols-3 gap-6">

            {{-- LEFT — Slider bobot --}}
            <div class="col-span-2">
                <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50">
                        <div class="text-sm font-bold text-gray-900">Bobot Kriteria Penilaian</div>
                        <div class="text-xs text-gray-400 mt-0.5">
                            Tentukan tingkat kepentingan (0–100) setiap kriteria.
                            <span class="text-amber-500 font-medium">Total bobot harus tepat 100.</span>
                        </div>
                    </div>

                    <div class="divide-y divide-gray-50">
                        @foreach ($kriteria as $k)
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between mb-1">
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
                            <p class="text-xs text-gray-400 mb-3 leading-relaxed">{{ $k->pertanyaan }}</p>
                            <input type="range" min="0" max="100" value="40" step="1"
                                   name="bobot[{{ $k->kode }}]"
                                   id="slider-{{ $k->kode }}"
                                   data-kode="{{ $k->kode }}"
                                   class="w-full h-1.5 bg-gray-200 rounded-full appearance-none cursor-pointer slider-input
                                          [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:h-4
                                          [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-indigo-600
                                          [&::-webkit-slider-thumb]:cursor-pointer
                                          [&::-moz-range-thumb]:w-4 [&::-moz-range-thumb]:h-4
                                          [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:bg-indigo-600 [&::-moz-range-thumb]:border-0"
                                   oninput="syncSlider(this)">
                            <div class="flex justify-between text-[10px] text-gray-300 mt-1">
                                <span>0</span><span>25</span><span>50</span><span>75</span><span>100</span>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 border-t border-gray-50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-400">Total Bobot</span>
                            <span id="total-bobot" class="text-sm font-black text-indigo-600">200</span>
                            <span id="bobot-warning" class="hidden text-[11px] font-medium text-red-500 bg-red-50 px-2 py-0.5 rounded-full">Harus 100!</span>
                            <span id="bobot-ok" class="hidden text-[11px] font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">✓</span>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('spk.tahap1') }}"
                               class="flex items-center gap-1.5 border border-gray-200 text-gray-600 text-sm font-medium px-4 py-2.5 rounded-xl hover:bg-gray-50 transition">
                                ← Kembali
                            </a>
                            <div class="relative group/btn">
                                <button type="submit" id="btn-hitung"
                                        class="flex items-center gap-2 bg-indigo-600 text-white text-sm font-semibold px-6 py-2.5 rounded-xl
                                               hover:bg-indigo-700 active:scale-95 transition-all
                                               disabled:opacity-40 disabled:cursor-not-allowed disabled:active:scale-100">
                                    🔢 Hitung Smart
                                </button>
                                {{-- Tooltip when disabled --}}
                                <div id="tooltip-btn" class="hidden absolute bottom-full right-0 mb-2 w-52 bg-gray-900 text-white text-xs px-3 py-2 rounded-xl pointer-events-none z-10">
                                    <div id="tooltip-msg"></div>
                                    <div class="absolute bottom-0 right-4 translate-y-full border-4 border-transparent border-t-gray-900"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT --}}
            <div class="col-span-1 flex flex-col gap-4">

                {{-- Hasil Ranking --}}
                <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-50 flex items-center gap-2">
                        <span>🏆</span>
                        <span class="text-xs font-bold text-gray-700">Hasil Ranking SMART</span>
                    </div>

                    {{-- Placeholder (belum hitung) --}}
                    <div id="ranking-placeholder" class="px-5 py-10 text-center">
                        <div class="text-4xl mb-3">🤖</div>
                        <p class="text-xs text-gray-400 leading-relaxed">
                            Hasil SMART akan muncul setelah Anda memilih BMW dan menekan tombol Hitung Smart.
                        </p>
                    </div>

                    {{-- Loading --}}
                    <div id="ranking-loading" class="hidden px-5 py-10 text-center">
                        <div class="w-8 h-8 border-2 border-indigo-200 border-t-indigo-600 rounded-full animate-spin mx-auto mb-3"></div>
                        <p class="text-xs text-gray-400">Menghitung...</p>
                    </div>

                    {{-- Result --}}
                    <div id="ranking-result" class="hidden">
                        <div id="ranking-list" class="divide-y divide-gray-50"></div>
                        <div class="px-5 py-3 border-t border-gray-50">
                            <button type="button" onclick="simpanHasil()"
                                    class="w-full flex items-center justify-center gap-2 bg-emerald-600 text-white text-xs font-bold py-2.5 rounded-xl hover:bg-emerald-700 transition">
                                ✓ Simpan & Lihat Detail Hasil
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Bobot ternormalisasi --}}
                <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-50">
                        <span class="text-[10px] font-bold text-gray-700 uppercase tracking-widest">Bobot Ternormalisasi</span>
                    </div>
                    <div class="px-5 py-4 flex flex-col gap-3">
                        @foreach ($kriteria as $k)
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs text-gray-600">{{ $k->nama }}</span>
                                <span id="norm-{{ $k->kode }}" class="text-xs font-bold text-indigo-600">20%</span>
                            </div>
                            <div class="h-1 bg-gray-100 rounded-full overflow-hidden">
                                <div id="bar-{{ $k->kode }}" class="h-full bg-indigo-400 rounded-full transition-all duration-300" style="width:20%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Info Tahap 2 --}}
                <div class="rounded-2xl px-5 py-5" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 40%, #1d4ed8 100%)">
                    <div class="text-xs font-bold text-white mb-2">Tentang Tahap 2</div>
                    <div class="text-xs text-blue-200 leading-relaxed">
                        Sistem SMART menghitung utility setiap model BMW berdasarkan bobot kriteria yang Anda tentukan, lalu meranking semua alternatif dari skor tertinggi ke terendah.
                    </div>
                </div>

                

            </div>
        </div>

       <div class="mt-6 bg-white border border-gray-100 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-lg">📊</span>
                    <div>
                        <div class="text-sm font-bold text-gray-900">Matriks Keputusan</div>
                        <div class="text-xs text-gray-400 mt-0.5">Menampilkan <span id="matriks-count" class="font-semibold text-indigo-600">0</span> model yang dipilih</div>
                    </div>
                </div>
                <div id="matriks-empty-hint" class="text-xs text-gray-300 italic">Pilih mobil untuk melihat data</div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-50 bg-gray-50/50">
                            <th class="px-5 py-3 text-left font-semibold text-gray-500 w-40">Model</th>
                            @foreach($kriteria as $k)
                            <th class="px-4 py-3 text-center font-semibold {{ $k->tipe === 'cost' ? 'text-blue-500' : 'text-emerald-500' }}">
                                {{ $k->nama }}
                                <div class="text-[9px] font-normal {{ $k->tipe === 'cost' ? 'text-blue-300' : 'text-emerald-300' }}">{{ ucfirst($k->tipe) }}</div>
                            </th>
                            @endforeach
                            <th class="px-4 py-3 text-center font-semibold text-gray-400">Skor SMART</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-matriks">
                        {{-- Empty state --}}
                        <tr id="matriks-placeholder">
                            <td colspan="{{ count($kriteria) + 2 }}" class="px-5 py-10 text-center text-gray-300 text-xs italic">
                                Belum ada model yang dipilih
                            </td>
                        </tr>

                        @foreach($kodeBodis as $mobil)
                        <tr class="matriks-row hidden hover:bg-gray-50/50 transition border-b border-gray-50"
                            data-id="{{ $mobil->id }}">
                            <td class="px-5 py-3">
                                <div class="font-semibold text-gray-900">{{ $mobil->nama_lengkap }}</div>
                                <div class="text-[10px] text-gray-400">{{ $mobil->tahun }}</div>
                            </td>
                            @foreach($kriteria as $k)
                            <td class="px-4 py-3 text-center">
                                @php $val = $nilaiMap[$mobil->id][$k->kode] ?? '—'; @endphp
                                @if(is_numeric($val))
                                    <div class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold
                                        {{ $val >= 4 ? 'bg-emerald-50 text-emerald-600' : ($val >= 3 ? 'bg-amber-50 text-amber-600' : 'bg-red-50 text-red-500') }}">
                                        {{ $val }}
                                    </div>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            @endforeach
                            <td class="px-4 py-3 text-center font-bold text-gray-300" id="skor-{{ $mobil->id }}">—</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </form>
</div>

<script>
const TOTAL_MOBIL = Number('{{ count($kodeBodis) }}');
let semuaDipilih = false;

// ── Pilih Semua ──────────────────────────────────────────────────
function togglePilihSemua() {
    semuaDipilih = !semuaDipilih;
    document.querySelectorAll('.mobil-checkbox').forEach(cb => {
        cb.checked = semuaDipilih;
    });
    const btn = document.getElementById('btn-semua');
    btn.textContent = semuaDipilih ? '☐ Batal Semua' : '☑ Pilih Semua';
    onMobilChange();
}

// ── Checkbox mobil ───────────────────────────────────────────────
function onMobilChange() {
    const checked = document.querySelectorAll('.mobil-checkbox:checked');
    const count   = checked.length;

    // Counter & progress bar
    document.getElementById('counter-pilih').textContent = count;
    document.getElementById('bar-pilih').style.width = (count / TOTAL_MOBIL * 100) + '%';

    // Update visual checkbox
    document.querySelectorAll('.mobil-checkbox').forEach(cb => {
        const card = cb.closest('label');
        const icon = card.querySelector('.check-icon');
        const box  = card.querySelector('.check-box-visual');
        if (cb.checked) {
            box.classList.add('border-indigo-500', 'bg-indigo-500');
            box.classList.remove('border-gray-200');
            icon.classList.remove('hidden');
        } else {
            box.classList.remove('border-indigo-500', 'bg-indigo-500');
            box.classList.add('border-gray-200');
            icon.classList.add('hidden');
        }
    });

    // Update matriks — tampilkan hanya yang dipilih
    const selectedIds = [...checked].map(c => c.value);
    const placeholder = document.getElementById('matriks-placeholder');
    const emptyHint   = document.getElementById('matriks-empty-hint');

    document.querySelectorAll('.matriks-row').forEach(row => {
        row.classList.toggle('hidden', !selectedIds.includes(row.dataset.id));
    });

    document.getElementById('matriks-count').textContent = count;
    placeholder.classList.toggle('hidden', count > 0);
    emptyHint.classList.toggle('hidden', count > 0);

    // Sync tombol pilih semua
    semuaDipilih = count === TOTAL_MOBIL;
    document.getElementById('btn-semua').textContent = semuaDipilih ? '☐ Batal Semua' : '☑ Pilih Semua';

    validateForm();
}

// ── Slider ───────────────────────────────────────────────────────
function syncSlider(input) {
    document.getElementById('val-' + input.dataset.kode).textContent = input.value;
    updateNormalized();
    validateForm();
}

function updateNormalized() {
    const sliders = document.querySelectorAll('.slider-input');
    let total = 0;
    const vals = {};
    sliders.forEach(s => {
        vals[s.dataset.kode] = parseFloat(s.value) || 0;
        total += vals[s.dataset.kode];
    });

    document.getElementById('total-bobot').textContent = total;
    const totalEl = document.getElementById('total-bobot');
    const warnEl  = document.getElementById('bobot-warning');
    const okEl    = document.getElementById('bobot-ok');

    if (total === 100) {
        totalEl.className = 'text-sm font-black text-emerald-600';
        warnEl.classList.add('hidden'); okEl.classList.remove('hidden');
    } else {
        totalEl.className = 'text-sm font-black text-indigo-600';
        warnEl.classList.remove('hidden'); okEl.classList.add('hidden');
    }

    Object.entries(vals).forEach(([kode, v]) => {
        const pct = total > 0 ? ((v / total) * 100).toFixed(1) : '0.0';
        const el  = document.getElementById('norm-' + kode);
        const bar = document.getElementById('bar-' + kode);
        if (el)  el.textContent  = pct + '%';
        if (bar) bar.style.width = pct + '%';
    });
}

// ── Validasi & tooltip ───────────────────────────────────────────
function validateForm() {
    const checkedCount = document.querySelectorAll('.mobil-checkbox:checked').length;
    const sliders = document.querySelectorAll('.slider-input');
    let total = 0;
    sliders.forEach(s => total += parseFloat(s.value) || 0);

    const btn     = document.getElementById('btn-hitung');
    const tooltip = document.getElementById('tooltip-btn');
    const msg     = document.getElementById('tooltip-msg');

    const issues = [];
    if (checkedCount < 2) issues.push('Pilih minimal 2 model BMW');
    if (total !== 100)    issues.push('Total bobot harus 100 (sekarang ' + total + ')');

    btn.disabled = issues.length > 0;

    btn.onmouseenter = () => {
        if (issues.length > 0) {
            msg.innerHTML = issues.map(i => '• ' + i).join('<br>');
            tooltip.classList.remove('hidden');
        }
    };
    btn.onmouseleave = () => tooltip.classList.add('hidden');

    // Warning pilih mobil
    document.getElementById('pesan-pilih').classList.toggle('hidden', checkedCount >= 2 || checkedCount === 0);
}

// ── AJAX Submit ─────────────────────────────────────────────────
let hasilTerakhir = null;

document.getElementById('form-tahap2').addEventListener('submit', async function(e) {
    e.preventDefault();

    const checked = document.querySelectorAll('.mobil-checkbox:checked').length;
    if (checked < 2) {
        document.getElementById('pesan-pilih').classList.remove('hidden');
        document.querySelector('.bg-white.border.border-gray-100').scrollIntoView({ behavior: 'smooth' });
        return;
    }

    // Show loading
    document.getElementById('ranking-placeholder').classList.add('hidden');
    document.getElementById('ranking-result').classList.add('hidden');
    document.getElementById('ranking-loading').classList.remove('hidden');

    try {
        const formData = new FormData(this);
        const response = await fetch('{{ route("spk.tahap2.hitung") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formData,
        });

        const data = await response.json();
        hasilTerakhir = data;

        // Render ranking
        renderRanking(data.ranked);

        // Update skor di matriks
        data.ranked.forEach(item => {
            const el = document.getElementById('skor-' + item.id);
            if (el) {
                el.textContent = (item.skor * 100).toFixed(2) + '%';
                el.className = 'px-4 py-3 text-center font-bold text-indigo-600';
            }
        });

        document.getElementById('ranking-loading').classList.add('hidden');
        document.getElementById('ranking-result').classList.remove('hidden');

        // Scroll ke ranking
        document.getElementById('ranking-result').scrollIntoView({ behavior: 'smooth', block: 'nearest' });

    } catch (err) {
        document.getElementById('ranking-loading').classList.add('hidden');
        document.getElementById('ranking-placeholder').classList.remove('hidden');
        console.error(err);
        alert('Terjadi kesalahan: ' + err.message);
    }
});

function renderRanking(ranked) {
    const medals = ['🥇','🥈','🥉'];
    const list = document.getElementById('ranking-list');
    list.innerHTML = ranked.map((item, i) => `
        <div class="px-5 py-3 flex items-center gap-3 ${i === 0 ? 'bg-indigo-50/50' : ''}">
            <div class="text-xl w-6 text-center flex-shrink-0">${medals[i] ?? '#' + (i+1)}</div>
            <div class="flex-1 min-w-0">
                <div class="text-xs font-bold text-gray-900 truncate">${item.nama}</div>
                <div class="text-[10px] text-gray-400">${item.tahun ?? ''}</div>
                <div class="mt-1 h-1 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full ${i === 0 ? 'bg-indigo-500' : 'bg-gray-300'} rounded-full"
                         style="width:${(item.skor * 100).toFixed(1)}%"></div>
                </div>
            </div>
            <div class="text-sm font-black ${i === 0 ? 'text-indigo-600' : 'text-gray-400'} flex-shrink-0">
                ${(item.skor * 100).toFixed(2)}%
            </div>
        </div>
    `).join('');
}

function simpanHasil() {
    if (!hasilTerakhir) return;
    // POST ke route hasil untuk simpan ke session lalu redirect
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("spk.tahap2.hitung") }}';
    form.innerHTML = document.getElementById('form-tahap2').innerHTML;
    // Tambah flag redirect
    const flag = document.createElement('input');
    flag.type = 'hidden'; flag.name = 'redirect'; flag.value = '1';
    form.appendChild(flag);
    document.body.appendChild(form);
    form.submit();
}

// Init
updateNormalized();
validateForm();
</script>
@endsection