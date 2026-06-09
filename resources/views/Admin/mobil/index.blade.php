@extends('admin.layout')

@section('page-title', 'Data Mobil')
@section('page-subtitle', 'Tambah, edit, dan hapus data model BMW')

@section('topbar-actions')
    <a href="{{ route('admin.mobil.create') }}" class="btn btn-primary">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
        Tambah Mobil
    </a>
@endsection

@section('content')

{{-- Tab filter seri --}}
<div class="tab-bar">
    <a href="{{ route('admin.mobil') }}" class="tab-btn {{ !request('seri') ? 'active' : '' }}">Semua</a>
    @foreach($seris as $s)
    <a href="{{ route('admin.mobil', ['seri'=>$s->id]) }}"
       class="tab-btn {{ request('seri') == $s->id ? 'active' : '' }}">
        {{ $s->nama }}
    </a>
    @endforeach
</div>

<div class="card">
    <table class="tbl">
        <thead>
            <tr>
                <th style="width:36px;">#</th>
                <th>Nama Lengkap</th>
                <th>Seri</th>
                <th>Kode</th>
                <th>Tahun</th>
                <th>Gambar</th>
                <th style="text-align:right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mobils as $i => $m)
            <tr>
                <td style="color:#94a3b8; font-size:12px;">{{ $mobils->firstItem() + $i }}</td>
                <td>
                    <div style="font-size:13.5px; font-weight:500; color:#0f172a;">{{ $m->nama_lengkap }}</div>
                </td>
                <td><span class="badge badge-blue">{{ $m->seri_nama }}</span></td>
                <td>
                    <span style="font-family:'Space Grotesk',sans-serif; font-size:12px; font-weight:600; background:#f1f5f9; padding:3px 7px; border-radius:5px; color:#475569;">{{ $m->kode }}</span>
                </td>
                <td style="font-size:13px; color:#475569;">{{ $m->tahun }}</td>
                <td>
                    @if($m->gambar)
                        <span style="font-size:12px; color:#059669;">✓ {{ $m->gambar }}</span>
                    @else
                        <span style="font-size:12px; color:#94a3b8;">—</span>
                    @endif
                </td>
                <td style="text-align:right;">
                    <div style="display:flex; align-items:center; justify-content:flex-end; gap:6px;">
                        <a href="{{ route('admin.mobil.edit', $m->id) }}" class="btn btn-ghost btn-sm">Edit</a>
                        <form method="POST" action="{{ route('admin.mobil.destroy', $m->id) }}"
                              onsubmit="return confirm('Hapus {{ $m->nama_lengkap }}?')" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger-ghost btn-sm">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="padding:48px; text-align:center; color:#94a3b8; font-size:13px;">
                    Tidak ada data mobil.
                    <a href="{{ route('admin.mobil.create') }}" style="color:#4f46e5; font-weight:500; margin-left:4px;">Tambah sekarang →</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($mobils->hasPages())
    <div style="padding:14px 20px; border-top:1px solid #f1f5f9; display:flex; align-items:center; justify-content:between; gap:16px;">
        <div style="font-size:12.5px; color:#64748b;">
            Menampilkan {{ $mobils->firstItem() }}–{{ $mobils->lastItem() }} dari {{ $mobils->total() }} model
        </div>
        <div class="pagination" style="margin-left:auto;">
            {{ $mobils->appends(request()->query())->links() }}
        </div>
    </div>
    @endif
</div>

@endsection