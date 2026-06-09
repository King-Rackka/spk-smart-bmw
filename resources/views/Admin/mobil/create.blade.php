@extends('admin.layout')

@section('page-title', 'Tambah Mobil')
@section('page-subtitle', 'Tambah data model BMW baru')

@section('topbar-actions')
    <a href="{{ route('admin.mobil') }}" class="btn btn-ghost">← Kembali</a>
@endsection

@section('content')

<form method="POST" action="{{ route('admin.mobil.store') }}" enctype="multipart/form-data">
    @csrf

    <div style="display:grid; grid-template-columns:1fr 340px; gap:24px; align-items:start;">

        {{-- Left: form utama --}}
        <div style="display:flex; flex-direction:column; gap:16px;">

            {{-- Info dasar --}}
            <div class="card">
                <div class="card-header"><div class="card-title">Informasi Dasar</div></div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Seri BMW <span style="color:#e11d48;">*</span></label>
                        <select name="seri_id" class="form-control form-select" required>
                            <option value="">— Pilih seri —</option>
                            @foreach($seris as $s)
                            <option value="{{ $s->id }}" {{ old('seri_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap <span style="color:#e11d48;">*</span></label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                                   class="form-control" placeholder="BMW Seri 3 (F30 320i)" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kode <span style="color:#e11d48;">*</span></label>
                            <input type="text" name="kode" value="{{ old('kode') }}"
                                   class="form-control" placeholder="F30" style="text-transform:uppercase;" required>
                            <div class="form-hint">Kode bodi / generasi, contoh: E46, F30, G20</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tahun Produksi <span style="color:#e11d48;">*</span></label>
                        <input type="text" name="tahun" value="{{ old('tahun') }}"
                               class="form-control" placeholder="2013–2017" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"
                                  placeholder="Deskripsi singkat tentang model ini...">{{ old('deskripsi') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Nilai kriteria --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Nilai Kriteria Tahap 2</div>
                    <span style="font-size:12px; color:#64748b;">Isi sesuai spesifikasi & harga pasar</span>
                </div>
                <div class="card-body">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        @foreach($kriteria as $k)
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">
                                {{ $k->nama }}
                                <span class="badge {{ $k->tipe==='benefit' ? 'badge-green' : 'badge-amber' }}" style="margin-left:4px; font-size:10px;">{{ $k->tipe }}</span>
                            </label>
                            <input type="number" name="nilai[{{ $k->kode }}]"
                                   value="{{ old('nilai.'.$k->kode) }}"
                                   class="form-control" step="0.01" min="0"
                                   placeholder="0" required>
                            @if(isset($k->satuan))
                            <div class="form-hint">Satuan: {{ $k->satuan }}</div>
                            @endif
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

                    {{-- Preview area --}}
                    <div id="img-preview-wrap" style="width:100%; aspect-ratio:16/9; border-radius:8px; background:#f8fafc; border:2px dashed #e2e8f0; display:flex; align-items:center; justify-content:center; overflow:hidden; margin-bottom:14px; cursor:pointer; transition:border-color 0.15s;"
                         onclick="document.getElementById('gambar-input').click()">
                        <img id="img-preview" src="" alt="" style="width:100%; height:100%; object-fit:cover; display:none; border-radius:6px;">
                        <div id="img-placeholder" style="text-align:center; padding:20px;">
                            <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="#cbd5e1" stroke-width="1.5" style="margin:0 auto 8px;">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                            <div style="font-size:12.5px; color:#94a3b8;">Klik untuk pilih gambar</div>
                            <div style="font-size:11px; color:#cbd5e1; margin-top:4px;">JPG, PNG, WEBP — maks 2MB</div>
                        </div>
                    </div>

                    <input type="file" id="gambar-input" name="gambar" accept="image/*"
                           style="display:none;" onchange="previewImage(this)">

                    <button type="button" onclick="document.getElementById('gambar-input').click()"
                            class="btn btn-ghost" style="width:100%; justify-content:center;">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                        Upload Gambar
                    </button>

                    <div id="file-name" style="margin-top:8px; font-size:12px; color:#64748b; text-align:center; display:none;"></div>

                    <div style="margin-top:10px; padding:10px 12px; background:#f0f9ff; border-radius:6px; font-size:12px; color:#0369a1; line-height:1.5;">
                        File akan disimpan di <code style="background:#e0f2fe; padding:1px 5px; border-radius:3px;">public/images/mobil/</code>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:12px;">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="m5 12 5 5L20 7"/>
                </svg>
                Simpan Mobil
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
        placeholder.style.display = 'none';
        nameEl.style.display = 'block';
        nameEl.textContent = '✓ ' + file.name + ' (' + (file.size/1024).toFixed(0) + ' KB)';

        document.getElementById('img-preview-wrap').style.borderColor = '#4f46e5';
        document.getElementById('img-preview-wrap').style.borderStyle = 'solid';
    };
    reader.readAsDataURL(file);
}
</script>

@endsection