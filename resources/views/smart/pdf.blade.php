<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family: Arial, sans-serif; font-size: 11px; color: #1e293b; background: #fff; }

    .header { background: linear-gradient(135deg, #0f172a, #1d4ed8); color: white; padding: 28px 32px; }
    .header h1 { font-size: 22px; font-weight: 900; margin-bottom: 4px; }
    .header p  { font-size: 11px; opacity: 0.7; }
    .header .meta { margin-top: 12px; display: flex; gap: 24px; }
    .header .meta span { font-size: 10px; opacity: 0.8; }

    .body { padding: 24px 32px; }

    .section-title { font-size: 12px; font-weight: 700; color: #4f46e5; text-transform: uppercase;
                     letter-spacing: 0.08em; margin-bottom: 8px; margin-top: 20px; }

    .winner-box { background: #eef2ff; border: 1.5px solid #c7d2fe; border-radius: 10px;
                  padding: 16px 20px; margin-bottom: 4px; }
    .winner-box .rank { font-size: 10px; color: #6366f1; font-weight: 700; margin-bottom: 3px; }
    .winner-box .name { font-size: 16px; font-weight: 900; color: #1e293b; }
    .winner-box .skor { font-size: 22px; font-weight: 900; color: #4f46e5; float: right; margin-top: -28px; }

    .skipped-badge { display: inline-block; background: #fef3c7; color: #92400e;
                     font-size: 9px; font-weight: 700; padding: 2px 8px; border-radius: 20px;
                     border: 1px solid #fde68a; margin-top: 4px; }

    table { width: 100%; border-collapse: collapse; margin-top: 8px; }
    th { background: #f8fafc; font-size: 9px; font-weight: 700; padding: 7px 10px;
         text-align: center; border-bottom: 1.5px solid #e2e8f0; }
    th.left { text-align: left; }
    th.cost { color: #3b82f6; }
    th.benefit { color: #10b981; }
    td { padding: 7px 10px; border-bottom: 1px solid #f1f5f9; font-size: 10px; text-align: center; }
    td.left { text-align: left; font-weight: 600; }
    tr.winner-row td { background: #eef2ff; }
    tr.winner-row td.left { color: #4f46e5; }

    .badge { display: inline-block; padding: 2px 7px; border-radius: 20px; font-size: 9px; font-weight: 700; }
    .badge-green { background: #d1fae5; color: #065f46; }
    .badge-blue  { background: #dbeafe; color: #1e40af; }
    .badge-amber { background: #fef3c7; color: #92400e; }
    .badge-red   { background: #fee2e2; color: #991b1b; }

    .bobot-row { display: flex; align-items: center; gap: 8px; margin-bottom: 6px; }
    .bobot-label { width: 160px; font-size: 10px; color: #475569; }
    .bobot-bar-wrap { flex: 1; height: 6px; background: #e2e8f0; border-radius: 3px; overflow: hidden; }
    .bobot-bar { height: 100%; background: #6366f1; border-radius: 3px; }
    .bobot-pct { width: 40px; text-align: right; font-size: 10px; font-weight: 700; color: #4f46e5; }

    .footer { border-top: 1px solid #e2e8f0; padding: 12px 32px; font-size: 9px; color: #94a3b8;
              display: flex; justify-content: space-between; margin-top: 24px; }

    .two-col { display: flex; gap: 20px; }
    .two-col > div { flex: 1; }

    .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 14px; margin-top: 8px; }
    .info-row { display: flex; justify-content: space-between; font-size: 10px;
                padding: 4px 0; border-bottom: 1px solid #f1f5f9; }
    .info-row:last-child { border-bottom: none; }
    .info-label { color: #64748b; }
    .info-value { font-weight: 700; color: #1e293b; }
</style>
</head>
<body>

{{-- HEADER --}}
<div class="header">
    <h1>Hasil Analisis SPK BMW</h1>
    <p>Sistem Pendukung Keputusan Pemilihan Mobil BMW — Metode SMART</p>
    <div class="meta">
        <span>👤 {{ $user->name }}</span>
        <span>📧 {{ $user->email }}</span>
        <span>📅 {{ \Carbon\Carbon::parse($analisis->created_at)->format('d M Y, H:i') }}</span>
        <span>🆔 Analisis #{{ $analisis->id }}</span>
    </div>
</div>

<div class="body">

    {{-- TAHAP 1 --}}
    <div class="section-title">Tahap 1 — Pemilihan Seri</div>
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Seri Terpilih</span>
            <span class="info-value">{{ $analisis->seri_nama }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Status Tahap 1</span>
            <span class="info-value">
                @if($skipped)
                    <span class="badge badge-amber">Dilewati — Dipilih Manual</span>
                @else
                    <span class="badge badge-green">Dihitung dengan SMART</span>
                @endif
            </span>
        </div>
    </div>

    {{-- WINNER --}}
    <div class="section-title">Rekomendasi Terbaik</div>
    <div class="winner-box">
        <div class="rank">🏆 #1 REKOMENDASI UTAMA</div>
        <div class="name">{{ $ranked[0]['nama'] }}</div>
        <div class="skor">{{ number_format($ranked[0]['skor']*100,2) }}%</div>
        <div style="font-size:10px;color:#64748b;margin-top:4px;">{{ $analisis->seri_nama }} · {{ $ranked[0]['tahun'] ?? '' }}</div>
    </div>

    <div class="two-col" style="margin-top:16px;">

        {{-- LEFT: Ranking --}}
        <div>
            <div class="section-title" style="margin-top:0">Ranking Lengkap</div>
            <table>
                <thead>
                    <tr>
                        <th class="left" style="width:24px">#</th>
                        <th class="left">Model</th>
                        <th>Skor SMART</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ranked as $i => $item)
                    <tr class="{{ $i===0?'winner-row':'' }}">
                        <td>{{ ['🥇','🥈','🥉'][$i] ?? $i+1 }}</td>
                        <td class="left">
                            {{ $item['nama'] }}
                            <div style="font-size:9px;color:#94a3b8;">{{ $item['tahun'] ?? '' }}</div>
                        </td>
                        <td style="font-weight:700;{{ $i===0?'color:#4f46e5':'' }}">
                            {{ number_format($item['skor']*100,2) }}%
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- RIGHT: Bobot --}}
        <div>
            <div class="section-title" style="margin-top:0">Bobot Kriteria</div>
            @foreach($kriteria as $k)
            @php $kode = $k->kode; $w = ($bobot[$kode] ?? 0)*100; @endphp
            <div class="bobot-row">
                <div class="bobot-label">{{ $k->nama }}</div>
                <div class="bobot-bar-wrap"><div class="bobot-bar" style="width:{{ $w }}%"></div></div>
                <div class="bobot-pct">{{ number_format($w,1) }}%</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- MATRIKS UTILITY --}}
    <div class="section-title">Breakdown Utility per Kriteria</div>
    <table>
        <thead>
            <tr>
                <th class="left">Model</th>
                @foreach($kriteria as $k)
                <th class="{{ $k->tipe==='cost'?'cost':'benefit' }}">
                    {{ $k->nama }}<br>
                    <span style="font-size:8px;font-weight:400">({{ $k->tipe }})</span>
                </th>
                @endforeach
                <th>Skor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ranked as $i => $item)
            <tr class="{{ $i===0?'winner-row':'' }}">
                <td class="left">{{ $item['nama'] }}</td>
                @foreach($kriteria as $k)
                @php $u = $utility[$item['id']][$k->kode] ?? 0; @endphp
                <td>
                    @if($u >= 0.75)
                        <span class="badge badge-green">{{ number_format($u,3) }}</span>
                    @elseif($u >= 0.5)
                        <span class="badge badge-blue">{{ number_format($u,3) }}</span>
                    @elseif($u >= 0.25)
                        <span class="badge badge-amber">{{ number_format($u,3) }}</span>
                    @else
                        <span class="badge badge-red">{{ number_format($u,3) }}</span>
                    @endif
                </td>
                @endforeach
                <td style="font-weight:700;{{ $i===0?'color:#4f46e5':'' }}">
                    {{ number_format($item['skor']*100,2) }}%
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- FORMULA --}}
    <div class="section-title">Formula SMART</div>
    <div class="info-box" style="font-size:10px;line-height:1.8;color:#475569;">
        <b>Normalisasi Bobot:</b> Wi = wi / Σwi &nbsp;&nbsp;
        <b>Utility Benefit:</b> u(xi) = (xi - xmin) / (xmax - xmin) &nbsp;&nbsp;
        <b>Utility Cost:</b> u(xi) = (xmax - xi) / (xmax - xmin) &nbsp;&nbsp;
        <b>Skor SMART:</b> S(Ai) = Σ [Wi × u(xij)]
    </div>

</div>

{{-- FOOTER --}}
<div class="footer">
    <span>BimmerGuide SPK BMW — Metode SMART</span>
    <span>Dicetak: {{ now()->format('d M Y H:i') }}</span>
</div>

</body>
</html>