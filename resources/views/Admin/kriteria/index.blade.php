@extends('admin.layout')

@section('page-title', 'Kriteria')
@section('page-subtitle', 'Kelola kriteria penilaian Tahap 1 dan Tahap 2')

@section('content')

<div style="display:grid; grid-template-columns:1fr 1fr; gap:24px;">

    {{-- ── TAHAP 1 ── --}}
    <div>
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:14px;">
            <div style="width:28px; height:28px; border-radius:7px; background:#ede9fe; color:#4f46e5; font-size:13px; font-weight:700; display:flex; align-items:center; justify-content:center; font-family:'Space Grotesk',sans-serif;">1</div>
            <h2 style="font-family:'Space Grotesk',sans-serif; font-size:15px; font-weight:600;">Kriteria Tahap 1 — Pemilihan Seri</h2>
        </div>

        <div style="display:flex; flex-direction:column; gap:12px;">
            @foreach($tahap1 as $k)
            <div class="card">
                <div class="card-header" style="padding:12px 16px;">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <span style="font-family:'Space Grotesk',sans-serif; font-size:11px; font-weight:700; background:#f1f5f9; color:#64748b; padding:2px 7px; border-radius:4px;">{{ $k->urutan }}</span>
                        <span style="font-size:13.5px; font-weight:500;">{{ $k->nama }}</span>
                        <span class="badge {{ $k->tipe === 'benefit' ? 'badge-green' : 'badge-amber' }}">{{ $k->tipe }}</span>
                    </div>
                    <button onclick="toggleEdit('k1-{{ $k->id }}')" class="btn btn-ghost btn-sm">Edit</button>
                </div>

                {{-- Preview --}}
                <div id="k1-{{ $k->id }}-preview" style="padding:10px 16px;">
                    <p style="font-size:12.5px; color:#64748b; line-height:1.5;">{{ $k->pertanyaan }}</p>
                </div>

                {{-- Edit form --}}
                <div id="k1-{{ $k->id }}" style="display:none; padding:16px; border-top:1px solid #f1f5f9;">
                    <form method="POST" action="{{ route('admin.kriteria.update', ['tahap'=>'1','id'=>$k->id]) }}">
                        @csrf @method('PUT')
                        <div class="form-group">
                            <label class="form-label">Nama Kriteria</label>
                            <input type="text" name="nama" value="{{ $k->nama }}" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Pertanyaan / Deskripsi</label>
                            <textarea name="pertanyaan" class="form-control" rows="2" required>{{ $k->pertanyaan }}</textarea>
                        </div>
                        <div class="form-grid-2">
                            <div class="form-group">
                                <label class="form-label">Tipe</label>
                                <select name="tipe" class="form-control form-select" required>
                                    <option value="benefit" {{ $k->tipe==='benefit'?'selected':'' }}>Benefit</option>
                                    <option value="cost" {{ $k->tipe==='cost'?'selected':'' }}>Cost</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Urutan</label>
                                <input type="number" name="urutan" value="{{ $k->urutan }}" class="form-control" min="1" required>
                            </div>
                        </div>
                        <div style="display:flex; gap:8px;">
                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                            <button type="button" onclick="toggleEdit('k1-{{ $k->id }}')" class="btn btn-ghost btn-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ── TAHAP 2 ── --}}
    <div>
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:14px;">
            <div style="width:28px; height:28px; border-radius:7px; background:#d1fae5; color:#059669; font-size:13px; font-weight:700; display:flex; align-items:center; justify-content:center; font-family:'Space Grotesk',sans-serif;">2</div>
            <h2 style="font-family:'Space Grotesk',sans-serif; font-size:15px; font-weight:600;">Kriteria Tahap 2 — Pemilihan Model</h2>
        </div>

        <div style="display:flex; flex-direction:column; gap:12px;">
            @foreach($tahap2 as $k)
            <div class="card">
                <div class="card-header" style="padding:12px 16px;">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <span style="font-family:'Space Grotesk',sans-serif; font-size:11px; font-weight:700; background:#f1f5f9; color:#64748b; padding:2px 7px; border-radius:4px;">{{ $k->urutan }}</span>
                        <span style="font-size:13.5px; font-weight:500;">{{ $k->nama }}</span>
                        <span class="badge {{ $k->tipe === 'benefit' ? 'badge-green' : 'badge-amber' }}">{{ $k->tipe }}</span>
                    </div>
                    <button onclick="toggleEdit('k2-{{ $k->id }}')" class="btn btn-ghost btn-sm">Edit</button>
                </div>

                <div id="k2-{{ $k->id }}-preview" style="padding:10px 16px;">
                    <p style="font-size:12.5px; color:#64748b; line-height:1.5;">{{ $k->pertanyaan ?? '—' }}</p>
                    @if(isset($k->satuan))
                    <span style="font-size:11px; background:#f1f5f9; padding:2px 7px; border-radius:4px; color:#64748b;">Satuan: {{ $k->satuan }}</span>
                    @endif
                </div>

                <div id="k2-{{ $k->id }}" style="display:none; padding:16px; border-top:1px solid #f1f5f9;">
                    <form method="POST" action="{{ route('admin.kriteria.update', ['tahap'=>'2','id'=>$k->id]) }}">
                        @csrf @method('PUT')
                        <div class="form-group">
                            <label class="form-label">Nama Kriteria</label>
                            <input type="text" name="nama" value="{{ $k->nama }}" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Pertanyaan / Deskripsi</label>
                            <textarea name="pertanyaan" class="form-control" rows="2" required>{{ $k->pertanyaan ?? '' }}</textarea>
                        </div>
                        <div class="form-grid-2">
                            <div class="form-group">
                                <label class="form-label">Tipe</label>
                                <select name="tipe" class="form-control form-select" required>
                                    <option value="benefit" {{ $k->tipe==='benefit'?'selected':'' }}>Benefit</option>
                                    <option value="cost" {{ $k->tipe==='cost'?'selected':'' }}>Cost</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Urutan</label>
                                <input type="number" name="urutan" value="{{ $k->urutan }}" class="form-control" min="1" required>
                            </div>
                        </div>
                        <div style="display:flex; gap:8px;">
                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                            <button type="button" onclick="toggleEdit('k2-{{ $k->id }}')" class="btn btn-ghost btn-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>

{{-- Info box --}}
<div style="margin-top:24px; padding:16px 20px; background:#f0f9ff; border:1px solid #bae6fd; border-radius:10px; font-size:13px; color:#0369a1;">
    <strong>ℹ️ Catatan:</strong> Tipe <strong>Benefit</strong> berarti nilai lebih besar = lebih baik (contoh: durabilitas, suku cadang).
    Tipe <strong>Cost</strong> berarti nilai lebih kecil = lebih baik (contoh: harga, biaya perawatan, konsumsi BBM).
    Perubahan tipe kriteria akan mempengaruhi semua perhitungan SMART.
</div>

<script>
function toggleEdit(id) {
    const form = document.getElementById(id);
    const preview = document.getElementById(id + '-preview');
    const isHidden = form.style.display === 'none';
    form.style.display = isHidden ? 'block' : 'none';
    if (preview) preview.style.display = isHidden ? 'none' : 'block';
}
</script>

@endsection