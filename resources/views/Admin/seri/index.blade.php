@extends('admin.layout')
 
@section('page-title', 'Data Seri')
@section('page-subtitle', 'Kelola seri BMW yang tersedia di sistem')
 
@section('content')
 
<div class="grid gap-5" style="grid-template-columns:repeat(auto-fill, minmax(340px,1fr));">
    @foreach($seris as $s)
    <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden flex flex-col shadow-sm">
 
        {{-- Header Card --}}
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-slate-900 text-white text-sm font-bold flex items-center justify-center flex-shrink-0">
                    {{ $s->id }}
                </div>
                <div>
                    <div class="text-sm font-semibold text-slate-900">{{ $s->nama }}</div>
                    <div class="text-xs text-slate-500">{{ $s->jumlah_model }} model tersedia</div>
                </div>
            </div>
            <button onclick="toggleSeri('seri-{{ $s->id }}')"
                    class="text-xs font-medium text-slate-600 bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 hover:bg-slate-100 transition-colors cursor-pointer">
                Edit
            </button>
        </div>
 
        {{-- Preview Mode --}}
        <div id="seri-{{ $s->id }}-preview" class="p-4 flex-grow flex flex-col justify-between gap-4">
            <p class="text-sm text-slate-500 leading-relaxed m-0">{{ $s->deskripsi ?? 'Belum ada deskripsi.' }}</p>
            <div>
                <a href="{{ route('admin.mobil', ['seri'=>$s->id]) }}"
                   class="inline-flex items-center gap-1.5 text-xs text-slate-600 bg-white border border-slate-200 rounded-lg px-3 py-1.5 no-underline hover:bg-slate-50 transition-colors">
                    🚗 Lihat {{ $s->jumlah_model }} Model
                </a>
            </div>
        </div>
 
        {{-- ═══ FORM EDIT GABUNGAN ═══ --}}
        <div id="seri-{{ $s->id }}" class="hidden border-t border-slate-100">
            <form method="POST" action="{{ route('admin.seri.updateAll', $s->id) }}" class="p-4">
                @csrf @method('PUT')
 
                {{-- Info Seri --}}
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-3 pb-1.5 border-b border-slate-100">
                    Info Seri
                </div>
 
                <div class="mb-3">
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Nama Seri</label>
                    <input type="text" name="nama" value="{{ $s->nama }}"
                           class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           required>
                </div>
 
                <div class="mb-5">
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Deskripsi</label>
                    <textarea name="deskripsi" rows="3"
                              class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none leading-relaxed">{{ $s->deskripsi }}</textarea>
                </div>
 
                {{-- Nilai Kriteria Tahap 1 --}}
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-3 pb-1.5 border-b border-slate-100">
                    Nilai Kriteria Tahap 1
                </div>
 
                <div class="flex flex-col divide-y divide-slate-100 mb-5">
                    @foreach($kriteria as $k)
                    @php
                        $nilaiRow = $nilaiMap[$s->id][$k->id] ?? null;
                        $val = $nilaiRow ? (int)$nilaiRow->nilai : 3;
                        $uid = 'rating-' . $s->id . '-' . $k->id;
                    @endphp
                    <div class="py-3 first:pt-0 last:pb-0">
                        <div class="flex items-center gap-1.5 mb-2">
                            <span class="text-sm font-medium text-slate-800">{{ $k->nama }}</span>
                            @if($k->tipe === 'benefit')
                                <span class="text-[10px] font-medium px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-700">↑ Benefit</span>
                            @else
                                <span class="text-[10px] font-medium px-1.5 py-0.5 rounded bg-rose-100 text-rose-700">↓ Cost</span>
                            @endif
                        </div>
 
                        <div class="flex items-center gap-1.5">
                            <input type="hidden" name="nilai[{{ $k->kode }}]" id="{{ $uid }}-input" value="{{ $val }}">
 
                            @for($i = 1; $i <= 5; $i++)
                            <button type="button"
                                    data-uid="{{ $uid }}"
                                    data-val="{{ $i }}"
                                    onclick="setRating('{{ $uid }}', {{ $i }})"
                                    class="rating-btn w-9 h-9 rounded-lg text-sm font-semibold border transition-all cursor-pointer flex items-center justify-center
                                           {{ $val >= $i
                                               ? 'bg-indigo-600 border-indigo-600 text-white'
                                               : 'bg-slate-50 border-slate-200 text-slate-400 hover:border-indigo-300 hover:text-indigo-400' }}">
                                {{ $i }}
                            </button>
                            @endfor
 
                            @php
                            $pillConfig = [
                                1 => ['bg-red-100 text-red-800 border border-red-200',         'Buruk'],
                                2 => ['bg-orange-100 text-orange-800 border border-orange-200','Kurang'],
                                3 => ['bg-yellow-100 text-yellow-800 border border-yellow-200','Cukup'],
                                4 => ['bg-green-100 text-green-800 border border-green-200',   'Baik'],
                                5 => ['bg-violet-100 text-violet-800 border border-violet-200','Sangat Baik'],
                            ];
                            @endphp
                            <span id="{{ $uid }}-pill"
                                  class="text-xs font-medium px-3 py-1 rounded-full min-w-[80px] text-center {{ $pillConfig[$val][0] }}">
                                {{ $pillConfig[$val][1] }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
 
                {{-- Tombol --}}
                <div class="flex gap-2">
                    <button type="submit"
                            class="flex-1 flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl py-2.5 border-none transition-colors cursor-pointer">
                        💾 Simpan Semua
                    </button>
                    <button type="button" onclick="toggleSeri('seri-{{ $s->id }}')"
                            class="px-4 text-sm font-medium text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors cursor-pointer">
                        Batal
                    </button>
                </div>
 
            </form>
        </div>
 
    </div>
    @endforeach
</div>
 
<div class="mt-6 px-4 py-3 bg-amber-50 border border-amber-200 rounded-xl text-sm text-amber-800">
    ⚠️ <strong>Perhatian:</strong> Slug seri tidak bisa diubah dari sini karena digunakan sebagai identifier di database dan URL.
</div>
 
<script>
const pillClasses = {
    1: { bg: 'bg-red-100 text-red-800 border border-red-200',         label: 'Buruk' },
    2: { bg: 'bg-orange-100 text-orange-800 border border-orange-200',label: 'Kurang' },
    3: { bg: 'bg-yellow-100 text-yellow-800 border border-yellow-200',label: 'Cukup' },
    4: { bg: 'bg-green-100 text-green-800 border border-green-200',   label: 'Baik' },
    5: { bg: 'bg-violet-100 text-violet-800 border border-violet-200',label: 'Sangat Baik' },
};
 
function setRating(uid, val) {
    document.getElementById(uid + '-input').value = val;
    document.querySelectorAll(`[data-uid="${uid}"]`).forEach(btn => {
        const btnVal = parseInt(btn.dataset.val);
        btn.className = 'rating-btn w-9 h-9 rounded-lg text-sm font-semibold border transition-all cursor-pointer flex items-center justify-center ';
        btn.className += btnVal <= val
            ? 'bg-indigo-600 border-indigo-600 text-white'
            : 'bg-slate-50 border-slate-200 text-slate-400 hover:border-indigo-300 hover:text-indigo-400';
    });
    const pill = document.getElementById(uid + '-pill');
    pill.className = `text-xs font-medium px-3 py-1 rounded-full min-w-[80px] text-center ${pillClasses[val].bg}`;
    pill.textContent = pillClasses[val].label;
}
 
function toggleSeri(id) {
    const form    = document.getElementById(id);
    const preview = document.getElementById(id + '-preview');
    const isHidden = form.classList.contains('hidden');
    form.classList.toggle('hidden', !isHidden);
    preview.classList.toggle('hidden', isHidden);
}
</script>
@endsection