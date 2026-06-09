@extends('admin.layout')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan sistem BimmerGuide')

@section('content')

{{-- Stats grid --}}
<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-icon blue">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
        </div>
        <div class="stat-value">{{ number_format($stats['total_user']) }}</div>
        <div class="stat-label">Total Pengguna</div>
    </div>

    <div class="stat-card green">
        <div class="stat-icon green">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
            </svg>
        </div>
        <div class="stat-value">{{ number_format($stats['total_analisis']) }}</div>
        <div class="stat-label">Total Analisis</div>
    </div>

    <div class="stat-card amber">
        <div class="stat-icon amber">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M5 17H3a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v9a2 2 0 0 1-2 2h-2"/>
                <circle cx="7.5" cy="17.5" r="2.5"/><circle cx="16.5" cy="17.5" r="2.5"/>
            </svg>
        </div>
        <div class="stat-value">{{ number_format($stats['total_mobil']) }}</div>
        <div class="stat-label">Model BMW</div>
    </div>

    <div class="stat-card rose">
        <div class="stat-icon rose">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 1-2-2V9m0 0h18"/>
            </svg>
        </div>
        <div class="stat-value">{{ number_format($stats['total_seri']) }}</div>
        <div class="stat-label">Seri BMW</div>
    </div>
</div>

{{-- Main row: top mobil + top seri + recent --}}
<div style="display:grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">

    {{-- Top mobil direkomendasikan --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Model Paling Direkomendasikan</div>
            <span class="badge badge-blue">Top 5</span>
        </div>
        <div style="overflow:hidden;">
            @php $maxTop = $topMobil->max('total') ?: 1; @endphp
            @forelse($topMobil as $i => $item)
            <div style="padding: 12px 20px; border-bottom: 1px solid #f1f5f9; {{ $loop->last ? 'border:none' : '' }}">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:6px;">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <div style="width:20px; height:20px; border-radius:5px; background:{{ ['#ede9fe','#dbeafe','#d1fae5','#fef3c7','#ffe4e6'][$i] }}; color:{{ ['#4f46e5','#2563eb','#059669','#d97706','#e11d48'][$i] }}; font-size:11px; font-weight:700; display:flex; align-items:center; justify-content:center; font-family:'Space Grotesk',sans-serif;">{{ $i+1 }}</div>
                        <span style="font-size:13px; font-weight:500;">{{ $item->nama_lengkap }}</span>
                    </div>
                    <span style="font-size:12px; font-weight:600; color:#4f46e5;">{{ $item->total }}×</span>
                </div>
                <div class="bar-wrap">
                    <div class="bar-fill" style="width:{{ round($item->total/$maxTop*100) }}%; background:{{ ['#4f46e5','#2563eb','#059669','#d97706','#e11d48'][$i] }};"></div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M9 17v-6h6v6M3 3l18 18"/></svg>
                <p>Belum ada analisis</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Seri + analisis per bulan --}}
    <div style="display:flex; flex-direction:column; gap:20px;">
        {{-- Top seri --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Seri Paling Banyak Dianalisis</div>
            </div>
            <div style="padding:16px 20px; display:flex; flex-wrap:wrap; gap:10px;">
                @php $totalSeriSum = $topSeri->sum('total') ?: 1; @endphp
                @foreach($topSeri as $s)
                <div style="flex:1; min-width:80px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px; text-align:center;">
                    <div style="font-family:'Space Grotesk',sans-serif; font-size:20px; font-weight:700; color:#0f172a;">{{ $s->total }}</div>
                    <div style="font-size:11.5px; color:#64748b; margin-top:2px;">{{ $s->nama }}</div>
                    <div style="margin-top:6px;">
                        <div class="bar-wrap"><div class="bar-fill" style="width:{{ round($s->total/$totalSeriSum*100) }}%"></div></div>
                    </div>
                </div>
                @endforeach
                @if($topSeri->isEmpty())
                <p style="font-size:13px; color:#64748b;">Belum ada data.</p>
                @endif
            </div>
        </div>

        {{-- Analisis per bulan --}}
        <div class="card" style="flex:1;">
            <div class="card-header">
                <div class="card-title">Analisis per Bulan</div>
                <span style="font-size:12px; color:#64748b;">6 bulan terakhir</span>
            </div>
            <div style="padding:16px 20px;">
                @php $maxBulan = $analisisPerBulan->max('total') ?: 1; @endphp
                @if($analisisPerBulan->isEmpty())
                    <p style="font-size:13px; color:#64748b;">Belum ada data.</p>
                @else
                <div style="display:flex; align-items:flex-end; gap:8px; height:80px;">
                    @foreach($analisisPerBulan as $b)
                    <div style="flex:1; display:flex; flex-direction:column; align-items:center; gap:4px; height:100%; justify-content:flex-end;">
                        <div style="font-size:10px; font-weight:600; color:#4f46e5;">{{ $b->total }}</div>
                        <div style="width:100%; background:#4f46e5; border-radius:3px 3px 0 0; height:{{ round($b->total/$maxBulan*100) }}%; min-height:4px; transition:height 0.6s;"></div>
                        <div style="font-size:9px; color:#94a3b8;">{{ \Carbon\Carbon::parse($b->bulan.'-01')->format('M') }}</div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Recent analisis --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">Analisis Terbaru</div>
        <a href="{{ route('admin.riwayat') }}" class="btn btn-ghost btn-sm">Lihat semua →</a>
    </div>
    <table class="tbl">
        <thead>
            <tr>
                <th>Pengguna</th>
                <th>Seri</th>
                <th>Rekomendasi</th>
                <th>Skor</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @forelse($analisisTerbaru as $a)
            <tr>
                <td>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <div style="width:28px; height:28px; border-radius:7px; background:#ede9fe; color:#4f46e5; font-size:11px; font-weight:700; display:flex; align-items:center; justify-content:center; font-family:'Space Grotesk',sans-serif;">{{ strtoupper(substr($a->user_name,0,1)) }}</div>
                        <span style="font-size:13px; font-weight:500;">{{ $a->user_name }}</span>
                    </div>
                </td>
                <td><span class="badge badge-gray">{{ $a->seri_nama }}</span></td>
                <td style="font-size:13px; font-weight:500;">{{ $a->model_nama }}</td>
                <td>
                    <div style="display:flex; align-items:center; gap:6px;">
                        <div class="bar-wrap" style="width:60px;"><div class="bar-fill" style="width:{{ round($a->skor_akhir*100) }}%;"></div></div>
                        <span style="font-size:12px; font-weight:600; color:#4f46e5;">{{ number_format($a->skor_akhir*100,1) }}%</span>
                    </div>
                </td>
                <td style="font-size:12px; color:#64748b;">{{ \Carbon\Carbon::parse($a->created_at)->diffForHumans() }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="padding:40px; text-align:center; color:#94a3b8; font-size:13px;">Belum ada analisis.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection