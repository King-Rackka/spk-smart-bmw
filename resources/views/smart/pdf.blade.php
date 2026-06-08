<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: Arial, sans-serif;
    font-size: 11px;
    color: #1e293b;
    background: #ffffff;
    line-height: 1.5;
}
.hdr-title { background-color: #1d4ed8; padding: 20px 32px 16px; }
.hdr-title h1 { font-size: 22px; font-weight: 900; color: #ffffff; margin-bottom: 3px; }
.hdr-title p  { font-size: 11px; color: #93c5fd; }
.hdr-meta     { background-color: #1e3a8a; padding: 9px 32px; }
.hdr-meta table { width: 100%; border-collapse: collapse; }
.hdr-meta td  { font-size: 10px; color: #bfdbfe; padding-right: 10px; vertical-align: middle; }
.ic {
    display: inline-block; width: 16px; height: 16px; line-height: 16px;
    text-align: center; background-color: #2563eb; border-radius: 3px;
    color: #ffffff; font-size: 9px; font-weight: 700;
    margin-right: 5px; vertical-align: middle;
}
.mv { color: #ffffff; font-weight: 700; }

.body { padding: 20px 32px; }

.st {
    font-size: 10px; font-weight: 700; color: #4f46e5;
    text-transform: uppercase; letter-spacing: 0.1em;
    padding-bottom: 5px; border-bottom: 2px solid #e0e7ff;
    margin-bottom: 10px; margin-top: 18px;
}
.st-first { margin-top: 0; }

.it { width: 100%; border-collapse: collapse; }
.it td { padding: 7px 12px; font-size: 11px; border-bottom: 1px solid #f1f5f9; background-color: #f8fafc; }
.it .lbl { color: #64748b; width: 38%; }
.it .val { font-weight: 700; color: #1e293b; }
.it tr:last-child td { border-bottom: none; }

.badge { display: inline-block; padding: 3px 9px; border-radius: 20px; font-size: 9px; font-weight: 700; }
.bg  { background-color: #d1fae5; color: #065f46; }
.bb  { background-color: #dbeafe; color: #1e40af; }
.ba  { background-color: #fef3c7; color: #92400e; }
.br  { background-color: #fee2e2; color: #991b1b; }
.bsk { background-color: #fef9c3; color: #854d0e; border: 1px solid #fde68a; }

.wc { border: 2px solid #c7d2fe; border-radius: 8px; overflow: hidden; }
.wc-bar { height: 5px; background-color: #4f46e5; }
.wc-inner { background-color: #eef2ff; }
.wc-inner table { width: 100%; border-collapse: collapse; }
.wc-inner td { vertical-align: middle; }
.wc-info { padding: 14px 18px; }
.wc-rank-lbl { font-size: 9px; font-weight: 700; color: #6366f1; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 4px; }
.wc-name { font-size: 18px; font-weight: 900; color: #1e293b; letter-spacing: -0.3px; }
.wc-sub  { font-size: 11px; color: #64748b; margin-top: 3px; }
.wc-score { width: 110px; background-color: #4f46e5; text-align: center; padding: 14px 10px; }
.wc-num  { font-size: 26px; font-weight: 900; color: #ffffff; line-height: 1; }
.wc-slbl { font-size: 9px; color: #c7d2fe; margin-top: 4px; }

.div { height: 1px; background-color: #e2e8f0; margin: 14px 0; }

.tc { width: 100%; border-collapse: collapse; }
.tc td { vertical-align: top; padding: 0; }
.tc .tleft  { width: 52%; padding-right: 14px; }
.tc .tright { width: 48%; }

.dt { width: 100%; border-collapse: collapse; border: 1px solid #e2e8f0; }
.dt thead tr { background-color: #f1f5f9; }
.dt th {
    font-size: 10px; font-weight: 700; color: #475569;
    padding: 8px 10px; text-align: center;
    border-bottom: 2px solid #e2e8f0; border-right: 1px solid #e2e8f0;
}
.dt th:last-child { border-right: none; }
.dt th.tl  { text-align: left; }
.dt th.tc-cost    { color: #1d4ed8; }
.dt th.tc-benefit { color: #047857; }
.dt td {
    padding: 8px 10px; border-bottom: 1px solid #f1f5f9;
    border-right: 1px solid #f1f5f9; font-size: 10.5px;
    text-align: center; vertical-align: middle;
}
.dt td:last-child { border-right: none; }
.dt td.tl { text-align: left; font-weight: 600; font-size: 10.5px; }
.dt tr.rw td { background-color: #eff6ff; }
.dt tr.rw td.tl { color: #3730a3; }
.dt tr:last-child td { border-bottom: none; }

.rb { display: inline-block; width: 22px; height: 22px; line-height: 22px; text-align: center; border-radius: 50%; font-size: 10px; font-weight: 700; }
.r1 { background-color: #fef08a; color: #713f12; }
.r2 { background-color: #e2e8f0; color: #334155; }
.r3 { background-color: #fed7aa; color: #7c2d12; }
.rn { background-color: #f1f5f9; color: #374151; }

.bb-row { width: 100%; border-collapse: collapse; margin-bottom: 7px; }
.bb-row td { padding: 0; vertical-align: middle; }
.bb-lbl { font-size: 10px; color: #475569; width: 145px; padding-right: 8px !important; line-height: 1.3; }
.bb-bg   { background-color: #e2e8f0; border-radius: 3px; height: 7px; }
.bb-fill { background-color: #6366f1; border-radius: 3px; height: 7px; }
.bb-pct  { font-size: 10px; font-weight: 700; color: #4338ca; width: 38px; text-align: right; padding-left: 6px !important; white-space: nowrap; }

.fb { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 14px; }
.fr { font-size: 10.5px; color: #475569; padding: 4px 0; border-bottom: 1px dotted #e2e8f0; }
.fr:last-child { border-bottom: none; }
.fr b { color: #374151; }
.fm  { font-family: "Courier New", monospace; color: #4338ca; font-size: 10px; }

.ftr { border-top: 2px solid #e2e8f0; background-color: #f8fafc; padding: 10px 32px; margin-top: 20px; }
.ftr table { width: 100%; border-collapse: collapse; }
.ftr td { font-size: 9.5px; color: #94a3b8; vertical-align: middle; }
.ftr .brand { font-weight: 700; color: #4f46e5; }
</style>
</head>
<body>

{{-- HEADER --}}
<div class="hdr-title">
    <h1>Hasil Analisis SPK BMW</h1>
    <p>Sistem Pendukung Keputusan Pemilihan Mobil BMW &mdash; Metode SMART</p>
</div>
<div class="hdr-meta">
    <table><tr>
        <td><span class="ic">U</span><span class="mv">{{ $user->name }}</span></td>
        <td><span class="ic">@</span><span class="mv">{{ $user->email }}</span></td>
        <td><span class="ic">T</span><span class="mv">{{ \Carbon\Carbon::parse($analisis->created_at)->locale('id')->isoFormat('D MMM YYYY, HH:mm') }}</span></td>
        <td><span class="ic">#</span><span class="mv">Analisis #{{ $analisis->id }}</span></td>
    </tr></table>
</div>

<div class="body">

{{-- TAHAP 1 --}}
<div class="st st-first">Tahap 1 &mdash; Pemilihan Seri BMW</div>
<table class="it">
    <tr>
        <td class="lbl">Seri Terpilih</td>
        <td class="val">{{ $analisis->seri_nama }}</td>
    </tr>
    <tr>
        <td class="lbl">Status Tahap 1</td>
        <td class="val">
            @if($skipped)
                <span class="badge bsk">Tahap 1 Dilewati &mdash; Seri Dipilih Manual</span>
            @else
                <span class="badge bg">Dihitung dengan Metode SMART</span>
            @endif
        </td>
    </tr>
</table>

{{-- WINNER CARD --}}
<div class="st">Rekomendasi Terbaik</div>
<div class="wc">
    <div class="wc-bar"></div>
    <div class="wc-inner">
        <table><tr>
            <td class="wc-info">
                <div class="wc-rank-lbl">No. 1 &mdash; Rekomendasi Utama</div>
                <div class="wc-name">{{ $ranked[0]['nama'] }}</div>
                <div class="wc-sub">{{ $analisis->seri_nama }} &nbsp;|&nbsp; {{ $ranked[0]['tahun'] ?? '' }}</div>
            </td>
            <td class="wc-score">
                <div class="wc-num">{{ number_format($ranked[0]['skor']*100, 2) }}%</div>
                <div class="wc-slbl">Skor SMART</div>
            </td>
        </tr></table>
    </div>
</div>

<div class="div"></div>

{{-- TWO COL: RANKING + BOBOT --}}
<table class="tc"><tr>

    <td class="tleft">
        <div class="st st-first">Ranking Lengkap</div>
        <table class="dt">
            <thead><tr>
                <th style="width:30px">#</th>
                <th class="tl">Model</th>
                <th style="width:74px">Skor SMART</th>
            </tr></thead>
            <tbody>
                @foreach($ranked as $i => $item)
                <tr class="{{ $i===0 ? 'rw' : '' }}">
                    <td>
                        @if($i===0)<span class="rb r1">1</span>
                        @elseif($i===1)<span class="rb r2">2</span>
                        @elseif($i===2)<span class="rb r3">3</span>
                        @else<span class="rb rn">{{ $i+1 }}</span>
                        @endif
                    </td>
                    <td class="tl">
                        {{ $item['nama'] }}
                        @if(!empty($item['tahun']))
                        <br><span style="font-size:9px;color:#94a3b8;font-weight:400">{{ $item['tahun'] }}</span>
                        @endif
                    </td>
                    <td style="font-weight:700;{{ $i===0 ? 'color:#3730a3' : '' }}">
                        {{ number_format($item['skor']*100, 2) }}%
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </td>

    <td class="tright">
        <div class="st st-first">Bobot Kriteria</div>
        @foreach($kriteria as $k)
        @php $kode = $k->kode; $w = ($bobot[$kode] ?? 0) * 100; @endphp
        <table class="bb-row"><tr>
            <td class="bb-lbl">{{ $k->nama }}</td>
            <td><div class="bb-bg"><div class="bb-fill" style="width:{{ $w }}%"></div></div></td>
            <td class="bb-pct">{{ number_format($w, 1) }}%</td>
        </tr></table>
        @endforeach
    </td>

</tr></table>

{{-- UTILITY MATRIX --}}
<div class="st">Breakdown Nilai Utility per Kriteria</div>
<table class="dt">
    <thead><tr>
        <th class="tl" style="min-width:105px">Model</th>
        @foreach($kriteria as $k)
        <th class="{{ $k->tipe === 'cost' ? 'tc-cost' : 'tc-benefit' }}">
            {{ $k->nama }}<br>
            <span style="font-size:8px;font-weight:400">({{ $k->tipe }})</span>
        </th>
        @endforeach
        <th style="color:#3730a3">Skor</th>
    </tr></thead>
    <tbody>
        @foreach($ranked as $i => $item)
        <tr class="{{ $i===0 ? 'rw' : '' }}">
            <td class="tl">
                {{ $item['nama'] }}
                @if(!empty($item['tahun']))
                <br><span style="font-size:9px;color:#94a3b8;font-weight:400">{{ $item['tahun'] }}</span>
                @endif
            </td>
            @foreach($kriteria as $k)
            @php $u = $utility[$item['id']][$k->kode] ?? 0; @endphp
            <td>
                @if($u >= 0.75)<span class="badge bg">{{ number_format($u,3) }}</span>
                @elseif($u >= 0.5)<span class="badge bb">{{ number_format($u,3) }}</span>
                @elseif($u >= 0.25)<span class="badge ba">{{ number_format($u,3) }}</span>
                @else<span class="badge br">{{ number_format($u,3) }}</span>
                @endif
            </td>
            @endforeach
            <td style="font-weight:700;{{ $i===0 ? 'color:#3730a3' : '' }}">
                {{ number_format($item['skor']*100, 2) }}%
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- FORMULA --}}
<div class="st">Dasar Formula Metode SMART</div>
<div class="fb">
    <div class="fr"><b>1. Normalisasi Bobot &nbsp;</b><span class="fm">Wi = wi / &sum;wi</span> &mdash; bobot dinormalisasi agar totalnya = 1</div>
    <div class="fr"><b>2. Utility Benefit &nbsp;</b><span class="fm">u(xi) = (xi &minus; xmin) / (xmax &minus; xmin)</span> &mdash; nilai lebih tinggi = lebih baik</div>
    <div class="fr"><b>3. Utility Cost &nbsp;</b><span class="fm">u(xi) = (xmax &minus; xi) / (xmax &minus; xmin)</span> &mdash; nilai lebih rendah = lebih baik</div>
    <div class="fr"><b>4. Skor Akhir SMART &nbsp;</b><span class="fm">S(Ai) = &sum; [Wi &times; u(xij)]</span> &mdash; alternatif dengan skor tertinggi adalah rekomendasi terbaik</div>
</div>

</div>{{-- /body --}}

</body>
</html>