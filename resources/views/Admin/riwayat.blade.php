@extends('admin.layout')

@section('page-title', 'Riwayat Analisis')
@section('page-subtitle', 'Semua analisis yang dilakukan pengguna')

@section('content')

{{-- Filter --}}
<form method="GET" style="margin-bottom:16px; display:flex; gap:10px; align-items:center;">
    <input type="text" name="user" value="{{ request('user') }}" placeholder="Cari nama pengguna..." class="form-control" style="max-width:260px;">
    <button type="submit" class="btn btn-ghost">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        Cari
    </button>
    @if(request('user'))
    <a href="{{ route('admin.riwayat') }}" class="btn btn-ghost">Reset</a>
    @endif
    <span style="font-size:12.5px; color:#64748b; margin-left:auto;">{{ $riwayat->total() }} analisis ditemukan</span>
</form>

<div class="card">
    <table class="tbl">
        <thead>
            <tr>
                <th>Pengguna</th>
                <th>Email</th>
                <th>Seri</th>
                <th>Rekomendasi</th>
                <th>Skor</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayat as $r)
            <tr>
                <td>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <div style="width:28px; height:28px; border-radius:7px; background:#ede9fe; color:#4f46e5; font-size:11px; font-weight:700; display:flex; align-items:center; justify-content:center; font-family:'Space Grotesk',sans-serif; flex-shrink:0;">{{ strtoupper(substr($r->user_name,0,1)) }}</div>
                        <span style="font-size:13px; font-weight:500;">{{ $r->user_name }}</span>
                    </div>
                </td>
                <td style="font-size:12.5px; color:#64748b;">{{ $r->user_email }}</td>
                <td><span class="badge badge-gray">{{ $r->seri_nama }}</span></td>
                <td style="font-size:13px; font-weight:500;">{{ $r->model_nama }}</td>
                <td>
                    <div style="display:flex; align-items:center; gap:6px;">
                        <div class="bar-wrap" style="width:50px;"><div class="bar-fill" style="width:{{ round($r->skor_akhir*100) }}%"></div></div>
                        <span style="font-size:12px; font-weight:600; color:#4f46e5;">{{ number_format($r->skor_akhir*100,1) }}%</span>
                    </div>
                </td>
                <td style="font-size:12px; color:#64748b;">
                    {{ \Carbon\Carbon::parse($r->created_at)->format('d M Y, H:i') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding:48px; text-align:center; color:#94a3b8; font-size:13px;">Belum ada riwayat analisis.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($riwayat->hasPages())
    <div style="padding:14px 20px; border-top:1px solid #f1f5f9; display:flex; align-items:center; gap:16px;">
        <div style="font-size:12.5px; color:#64748b;">
            {{ $riwayat->firstItem() }}–{{ $riwayat->lastItem() }} dari {{ $riwayat->total() }}
        </div>
        <div class="pagination" style="margin-left:auto;">
            {{ $riwayat->appends(request()->query())->links() }}
        </div>
    </div>
    @endif
</div>

@endsection