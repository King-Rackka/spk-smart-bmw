@extends('admin.layout')

@section('page-title', 'Edit Mobil')
@section('page-subtitle', '{{ $mobil->nama_lengkap }}')

@section('topbar-actions')
    <a href="{{ route('admin.mobil') }}" class="btn btn-ghost">← Kembali</a>
@endsection

@section('content')

<form method="POST" action="{{ route('admin.mobil.update', $mobil->id) }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div style="display:grid; grid-template-columns:1fr 340px; gap:24px; align-items:start;">

        {{-- Left --}}
        <div style="display:flex; flex-direction:column; gap:16px;">

            <div class="card">
                <div class="card-header"><div class="card-title">Informasi Dasar</div></div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Seri BMW <span style="color:#e11d48;">*</span></label>
                        <select name="seri_id" class="form-control form-select" required>
                            @foreach($seris as $s)
                            <option value="{{ $s->id }}" {{ $mobil->seri_id == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap <span style="color:#e11d48;">*</span></label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $mobil->nama_lengkap) }}"
                                   class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kode <span style="color:#e11d48;">*</span></label>
                            <input type="text" name="kode" value="{{ old('kode', $mobil->kode) }}"
                                   class="form-control" style="text-transform:uppercase;" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tahun Produksi <span style="color:#e11d48;">*</span></label>
                        <input type="text" name="tahun" value="{{ old('tahun', $mobil->tahun) }}"
                               class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $mobil->deskripsi) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Nilai Kriteria Tahap 2</div>
                </div>
                <div class="card-body">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        @foreach($kriteria as $k)
                        @php $val = $nilaiMap[$k->id]->nilai ?? 0; @endphp
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">
                                {{ $k->nama }}
                                <span class="badge {{ $k->tipe==='benefit' ? 'badge-green' : 'badge-amber' }}" style="margin-left:4px; font-size:10px;">{{ $k->tipe }}</span>
                            </label>
                            <input type="number" name="nilai[{{ $k->kode }}]"
                                   value="{{ old('nilai.'.$k->kode, $val) }}"
                                   class="form-control" step="0.01" min="0" required>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

        {{-- Right: gambar --}}
        <div style="display:flex; flex-direction:column; gap:16px;">
            <div class="card">
                <div class="card-header"><div class="card-title">Foto Mobil</div></div>
                <div class="card-body">

                    {{-- Preview existing image --}}
                    <div id="img-preview-wrap"
                         style="width:100%; aspect-ratio:16/9; border-radius:8px; overflow:hidden; margin-bottom:14px; cursor:pointer; border:2px solid {{ $mobil->gambar ? '#e2e8f0' : 'dashed #e2e8f0' }}; background:#f8fafc; display:flex; align-items:center; justify-content:center; transition:border-color 0.15s;"
                         onclick="document.getElementById('gambar-input').click()">
                        @if($mobil->gambar)
                            <img id="img-preview"
                                 src="{{ asset('images/mobil/'.$mobil->gambar) }}"
                                 alt="{{ $mobil->nama_lengkap }}"
                                 style="width:100%; height:100%; object-fit:cover;"
                                 onerror="this.style.display='none'; document.getElementById('img-placeholder').style.display='flex'">
                            <div id="img-placeholder" style="display:none; flex-direction:column; align-items:center; justify-content:center; width:100%; padding:20px; text-align:center;">
                                <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="#cbd5e1" stroke-width="1.5" style="margin-bottom:8px;">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <polyline points="21 15 16 10 5 21"/>
                                </svg>
                                <div style="font-size:12px; color:#94a3b8;">File tidak ditemukan</div>
                            </div>
                        @else
                            <img id="img-preview" src="" alt="" style="width:100%; height:100%; object-fit:cover; display:none;">
                            <div id="img-placeholder" style="text-align:center; padding:20px;">
                                <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="#cbd5e1" stroke-width="1.5" style="margin:0 auto 8px;">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <polyline points="21 15 16 10 5 21"/>
                                </svg>
                                <div style="font-size:12.5px; color:#94a3b8;">Belum ada gambar</div>
                                <div style="font-size:11px; color:#cbd5e1; margin-top:4px;">Klik untuk upload</div>
                            </div>
                        @endif
                    </div>

                    @if($mobil->gambar)
                    <div style="margin-bottom:10px; padding:8px 12px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:6px; font-size:12px; color:#166534; display:flex; align-items:center; gap:6px;">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="m5 12 5 5L20 7"/></svg>
                        {{ $mobil->gambar }}
                    </div>
                    @endif

                    <input type="file" id="gambar-input" name="gambar" accept="image/*"
                           style="display:none;" onchange="previewImage(this)">

                    <button type="button" onclick="document.getElementById('gambar-input').click()"
                            class="btn btn-ghost" style="width:100%; justify-content:center;">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                        {{ $mobil->gambar ? 'Ganti Gambar' : 'Upload Gambar' }}
                    </button>

                    <div id="file-name" style="margin-top:8px; font-size:12px; color:#64748b; text-align:center; display:none;"></div>

                    <div style="margin-top:10px; padding:10px 12px; background:#f0f9ff; border-radius:6px; font-size:12px; color:#0369a1; line-height:1.5;">
                        Kosongkan jika tidak ingin mengganti gambar.<br>
                        Disimpan di <code style="background:#e0f2fe; padding:1px 5px; border-radius:3px;">public/images/mobil/</code>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:12px;">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="m5 12 5 5L20 7"/>
                </svg>
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.mobil') }}" class="btn btn-ghost" style="width:100%; justify-content:center;">Batal</a>
        </div>

    </div>
</form>

<script>
function previewImage(input) {
    const file = input.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = e => {
        const img = document.getElementById('img-preview');
        const placeholder = document.getElementById('img-placeholder');
        const nameEl = document.getElementById('file-name');

        img.src = e.target.result;
        img.style.display = 'block';
        if (placeholder) placeholder.style.display = 'none';
        nameEl.style.display = 'block';
        nameEl.textContent = '✓ ' + file.name + ' (' + (file.size/1024).toFixed(0) + ' KB)';

        const wrap = document.getElementById('img-preview-wrap');
        wrap.style.borderColor = '#4f46e5';
        wrap.style.borderStyle = 'solid';
    };
    reader.readAsDataURL(file);
}
</script>

@endsection