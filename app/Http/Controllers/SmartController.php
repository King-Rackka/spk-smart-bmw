<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\SmartService;
use Illuminate\Support\Facades\Auth;

class SmartController extends Controller
{
    public function __construct(private SmartService $smart) {}

    // ── TAHAP 1 ─────────────────────────────────────────────────

    public function tahap1()
{
    $kriteria = DB::table('kriteria_tahap1')->orderBy('urutan')->get();
 
    // Ambil semua seri + nilai untuk preview client-side
    $serisRaw = DB::table('seris')->get();
    $seris = $serisRaw->map(function ($seri) use ($kriteria) {
        $nilaiRows = DB::table('nilai_seri')
            ->where('seri_id', $seri->id)
            ->get()
            ->keyBy('kriteria_id');
 
        $nilai = [];
        foreach ($kriteria as $k) {
            $nilai[$k->kode] = (float) ($nilaiRows[$k->id]->nilai ?? 0);
        }
 
        return [
            'id'    => $seri->id,
            'nama'  => $seri->nama,
            'slug'  => $seri->slug,
            'nilai' => $nilai,
        ];
    })->values();
 
    return view('smart.tahap1', compact('kriteria', 'seris'));
}

    public function hitungTahap1(Request $request)
    {
        $request->validate([
            'bobot' => 'required|array',
            'bobot.*' => 'required|numeric|min:1|max:100',
        ]);

        // Ambil semua seri + nilainya
        $seris = DB::table('seris')->get();
        $kriteria = DB::table('kriteria_tahap1')->orderBy('urutan')->get();

        $alternatif = [];
        foreach ($seris as $seri) {
            $nilaiRows = DB::table('nilai_seri')
                ->where('seri_id', $seri->id)
                ->get()
                ->keyBy('kriteria_id');

            $nilai = [];
            foreach ($kriteria as $k) {
                $nilai[$k->kode] = (float) ($nilaiRows[$k->id]->nilai ?? 0);
            }

            $alternatif[] = [
                'id'    => $seri->id,
                'nama'  => $seri->nama,
                'slug'  => $seri->slug,
                'nilai' => $nilai,
            ];
        }

        $kriteriaArr = $kriteria->map(fn($k) => [
            'kode' => $k->kode,
            'tipe' => $k->tipe,
        ])->toArray();

        // Bobot dari request
        $bobotInput = [];
        foreach ($kriteria as $k) {
            $bobotInput[$k->kode] = (float) ($request->bobot[$k->kode] ?? 1);
        }

        $hasil = $this->smart->proses($alternatif, $kriteriaArr, $bobotInput);

        // Simpan ke session
        session([
            'tahap1_hasil'    => $hasil['ranked'],
            'tahap1_bobot'    => $hasil['bobotNormal'],
            'tahap1_winner'   => $hasil['ranked'][0],
        ]);

        return redirect()->route('spk.tahap2');
    }

    // ── TAHAP 2 ─────────────────────────────────────────────────

    public function tahap2()
{
    if (!session('tahap1_winner')) {
        return redirect()->route('spk.tahap1');
    }
 
    $winner   = session('tahap1_winner');
    $seri     = DB::table('seris')->find($winner['id']);
    $kriteria = DB::table('kriteria_tahap2')->orderBy('urutan')->get();
 
    // Ambil semua kode bodi seri terpilih
    $kodeBodis = DB::table('kode_bodis')
        ->where('seri_id', $winner['id'])
        ->get();
 
    // Buat nilaiMap[mobil_id][kode_kriteria] = nilai
    $nilaiMap = [];
    foreach ($kodeBodis as $kb) {
        $rows = DB::table('nilai_kode_bodi')
            ->where('kode_bodi_id', $kb->id)
            ->get()
            ->keyBy('kriteria_id');
 
        foreach ($kriteria as $k) {
            $nilaiMap[$kb->id][$k->kode] = $rows[$k->id]->nilai ?? '—';
        }
    }
 
    return view('smart.tahap2', compact('winner', 'seri', 'kriteria', 'kodeBodis', 'nilaiMap'));
}
 
public function hitungTahap2(Request $request)
{
    $request->validate([
        'bobot'          => 'required|array',
        'bobot.*'        => 'required|numeric|min:0|max:100',
        'selected_mobil' => 'required|array|min:2',
    ]);
 
    $winner = session('tahap1_winner');
    if (!$winner) return redirect()->route('spk.tahap1');
 
    $kriteria    = DB::table('kriteria_tahap2')->orderBy('urutan')->get();
    $selectedIds = $request->selected_mobil;
 
    $kodeBodis = DB::table('kode_bodis')
        ->whereIn('id', $selectedIds)
        ->where('seri_id', $winner['id'])
        ->get();
 
    $alternatif = [];
    foreach ($kodeBodis as $kb) {
        $nilaiRows = DB::table('nilai_kode_bodi')
            ->where('kode_bodi_id', $kb->id)
            ->get()->keyBy('kriteria_id');
 
        $nilai = [];
        foreach ($kriteria as $k) {
            $nilai[$k->kode] = (float) ($nilaiRows[$k->id]->nilai ?? 0);
        }
        $alternatif[] = ['id'=>$kb->id,'nama'=>$kb->nama_lengkap,'tahun'=>$kb->tahun,'nilai'=>$nilai];
    }
 
    $kriteriaArr = $kriteria->map(fn($k) => [
        'kode'=>$k->kode,'tipe'=>$k->tipe,'nama'=>$k->nama,
    ])->toArray();
 
    $bobotInput = [];
    foreach ($kriteria as $k) {
        $bobotInput[$k->kode] = (float)($request->bobot[$k->kode] ?? 0);
    }
 
    $hasil = $this->smart->proses($alternatif, $kriteriaArr, $bobotInput);
 
    session([
        'tahap2_hasil'    => $hasil['ranked'],
        'tahap2_bobot'    => $hasil['bobotNormal'],
        'tahap2_kriteria' => $kriteriaArr,
        'tahap2_utility'  => $hasil['utility'] ?? [],
    ]);
 
    // Kalau AJAX → return JSON
    if ($request->expectsJson() || !$request->has('redirect')) {
        return response()->json([
            'ranked'      => $hasil['ranked'],
            'bobotNormal' => $hasil['bobotNormal'],
        ]);
    }
 
    // Simpan ke DB
    $winner    = session('tahap1_winner');
    $ranked    = $hasil['ranked'];
    $best      = $ranked[0];
    $skipped   = session('tahap1_skipped', false);
 
    $hasilId = DB::table('hasil_analisis')->insertGetId([
        'user_id'        => Auth::id(),
        'seri_id'        => $winner['id'],
        'kode_bodi_id'   => $best['id'],
        'bobot_tahap1'   => $skipped ? null : json_encode(session('tahap1_bobot')),
        'bobot_tahap2'   => json_encode($hasil['bobotNormal']),
        'ranking_tahap1' => $skipped ? null : json_encode(session('tahap1_hasil')),
        'ranking_tahap2' => json_encode($ranked),
        'skor_akhir'     => $best['skor'],
        'created_at'     => now(),
        'updated_at'     => now(),
    ]);
 
    session(['hasil_id' => $hasilId]);
 
    return redirect()->route('spk.hasil');
}

    public function skipTahap1(Request $request)
{
    $request->validate(['seri_id' => 'required|integer|exists:seris,id']);
 
    $seri = DB::table('seris')->find($request->seri_id);
 
    // Simpan winner ke session (tanpa skor, karena skip)
    session([
        'tahap1_winner' => [
            'id'   => $seri->id,
            'nama' => $seri->nama,
            'slug' => $seri->slug,
            'skor' => null,   // null = skip
            'rank' => null,
        ],
        'tahap1_hasil'  => null,
        'tahap1_bobot'  => null,
        'tahap1_skipped' => true,
    ]);
 
    return redirect()->route('spk.tahap2');
}

    // ── HASIL ────────────────────────────────────────────────────

    public function hasil()
{
    $ranked    = session('tahap2_hasil');
    $bobot     = session('tahap2_bobot');
    $kriteria  = session('tahap2_kriteria');
    $utility   = session('tahap2_utility', []);
    $winner    = session('tahap1_winner');
    $skipped   = session('tahap1_skipped', false);
    $hasilId   = session('hasil_id');
 
    if (!$ranked || !$winner) return redirect()->route('spk.tahap1');
 
    $seri    = DB::table('seris')->find($winner['id']);
    $best    = $ranked[0];
    $bestDetail = DB::table('kode_bodis')->find($best['id']);
 
    return view('smart.hasil', compact(
        'ranked','bobot','kriteria','utility',
        'winner','seri','best','bestDetail',
        'skipped','hasilId'
    ));
}
 
// ── RIWAYAT ──────────────────────────────────────────────────────
public function riwayat()
{
    $riwayat = DB::table('hasil_analisis')
        ->join('seris',      'hasil_analisis.seri_id',      '=', 'seris.id')
        ->join('kode_bodis', 'hasil_analisis.kode_bodi_id', '=', 'kode_bodis.id')
        ->where('hasil_analisis.user_id', Auth::id())
        ->select(
            'hasil_analisis.*',
            'seris.nama as seri_nama',
            'kode_bodis.nama_lengkap as model_nama',
            'kode_bodis.gambar as model_gambar',
        )
        ->orderByDesc('hasil_analisis.created_at')
        ->paginate(10);
 
    return view('smart.riwayat', compact('riwayat'));
}
 
// ── DETAIL RIWAYAT ───────────────────────────────────────────────
public function riwayatDetail($id)
{
    $analisis = DB::table('hasil_analisis')
        ->join('seris',      'hasil_analisis.seri_id',      '=', 'seris.id')
        ->join('kode_bodis', 'hasil_analisis.kode_bodi_id', '=', 'kode_bodis.id')
        ->where('hasil_analisis.id', $id)
        ->where('hasil_analisis.user_id', Auth::id())
        ->select('hasil_analisis.*','seris.nama as seri_nama','kode_bodis.nama_lengkap as model_nama','kode_bodis.gambar as model_gambar')
        ->first();
 
    abort_if(!$analisis, 404);
 
    $ranked   = json_decode($analisis->ranking_tahap2, true);
    $bobot    = json_decode($analisis->bobot_tahap2, true);
    $kriteria = DB::table('kriteria_tahap2')->orderBy('urutan')->get()->map(fn($k)=>[
        'kode'=>$k->kode,'tipe'=>$k->tipe,'nama'=>$k->nama,
    ])->toArray();
 
    $skipped  = is_null($analisis->bobot_tahap1);
    $winner   = ['id'=>$analisis->seri_id,'nama'=>$analisis->seri_nama];
 
    return view('smart.hasil', [
        'ranked'     => $ranked,
        'bobot'      => $bobot,
        'kriteria'   => $kriteria,
        'utility'    => [],
        'winner'     => $winner,
        'seri'       => (object)['id'=>$analisis->seri_id,'nama'=>$analisis->seri_nama],
        'best'       => $ranked[0],
        'bestDetail' => DB::table('kode_bodis')->find($ranked[0]['id']),
        'skipped'    => $skipped,
        'hasilId'    => $analisis->id,
        'fromRiwayat'=> true,
    ]);
}
 
// ── EXPORT PDF ───────────────────────────────────────────────────
public function exportPdf($id)
{
    $analisis = DB::table('hasil_analisis')
        ->join('seris',      'hasil_analisis.seri_id',      '=', 'seris.id')
        ->join('kode_bodis', 'hasil_analisis.kode_bodi_id', '=', 'kode_bodis.id')
        ->where('hasil_analisis.id', $id)
        ->where('hasil_analisis.user_id', Auth::id())
        ->select('hasil_analisis.*','seris.nama as seri_nama','kode_bodis.nama_lengkap as model_nama','kode_bodis.gambar as model_gambar')
        ->first();
 
    abort_if(!$analisis, 404);
 
    $user     = Auth::user();
    $ranked   = json_decode($analisis->ranking_tahap2, true);
    $bobot    = json_decode($analisis->bobot_tahap2, true);
    $kriteria = DB::table('kriteria_tahap2')->orderBy('urutan')->get();
    $skipped  = is_null($analisis->bobot_tahap1);
 
    // Hitung ulang utility untuk breakdown
    $utility = $this->hitungUtilityBreakdown($ranked, $kriteria);
 
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('smart.pdf', compact(
        'analisis','user','ranked','bobot','kriteria','utility','skipped'
    ))->setPaper('a4','portrait');
 
    return $pdf->download('hasil-analisis-bmw-' . $id . '.pdf');
}
 
// ── HELPER: hitung utility dari ranked data ───────────────────────
private function hitungUtilityBreakdown($ranked, $kriteria)
{
    $utility = [];
    foreach ($kriteria as $k) {
        $vals = array_column(array_map(fn($r) => $r['nilai'][$k->kode] ?? 0, $ranked), null);
        $vals = array_map(fn($r) => $r['nilai'][$k->kode] ?? 0, $ranked);
        $min  = min($vals);
        $max  = max($vals);
        foreach ($ranked as $alt) {
            $v = $alt['nilai'][$k->kode] ?? 0;
            if ($max === $min) {
                $u = 1;
            } elseif ($k->tipe === 'cost') {
                $u = ($max - $v) / ($max - $min);
            } else {
                $u = ($v - $min) / ($max - $min);
            }
            $utility[$alt['id']][$k->kode] = round($u, 4);
        }
    }
    return $utility;
}

    public function reset()
    {
        session()->forget([
            'tahap1_hasil', 'tahap1_bobot', 'tahap1_winner',
            'tahap2_hasil', 'tahap2_bobot', 'tahap2_kriteria',
        ]);
        return redirect()->route('spk.tahap1');
    }
}