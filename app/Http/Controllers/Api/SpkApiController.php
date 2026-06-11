<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SmartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SpkApiController extends Controller
{
    public function __construct(private SmartService $smart) {}

    // GET /api/kriteria/tahap1 — list kriteria + nilai tiap seri
    public function kriteriaTahap1()
    {
        $kriteria = DB::table('kriteria_tahap1')->orderBy('urutan')->get();

        $seris = DB::table('seris')->get()->map(function ($seri) use ($kriteria) {
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

        return response()->json([
            'kriteria' => $kriteria,
            'seris'    => $seris,
        ]);
    }

    // GET /api/kriteria/tahap2?seri_id=1 — list kriteria + nilai tiap kode bodi
    public function kriteriaTahap2(Request $request)
    {
        $request->validate(['seri_id' => 'required|integer|exists:seris,id']);

        $kriteria  = DB::table('kriteria_tahap2')->orderBy('urutan')->get();
        $kodeBodis = DB::table('kode_bodis')->where('seri_id', $request->seri_id)->get();

        $nilaiMap = [];
        foreach ($kodeBodis as $kb) {
            $rows = DB::table('nilai_kode_bodi')
                ->where('kode_bodi_id', $kb->id)
                ->get()
                ->keyBy('kriteria_id');

            $nilai = [];
            foreach ($kriteria as $k) {
                $nilai[$k->kode] = (float) ($rows[$k->id]->nilai ?? 0);
            }

            $nilaiMap[] = [
                'id'           => $kb->id,
                'nama_lengkap' => $kb->nama_lengkap,
                'kode'         => $kb->kode,
                'tahun'        => $kb->tahun,
                'gambar'       => $kb->gambar ?? null,
                'nilai'        => $nilai,
            ];
        }

        return response()->json([
            'kriteria'   => $kriteria,
            'kode_bodis' => $nilaiMap,
        ]);
    }

    // POST /api/spk/tahap1 — hitung & return ranking seri
    // Body: { "bobot": { "kapasitas": 70, "ground": 20, ... } }
    public function hitungTahap1(Request $request)
    {
        $request->validate([
            'bobot'   => 'required|array',
            'bobot.*' => 'required|numeric|min:1|max:100',
        ]);

        $kriteria = DB::table('kriteria_tahap1')->orderBy('urutan')->get();
        $seris    = DB::table('seris')->get();

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

        $bobotInput = [];
        foreach ($kriteria as $k) {
            $bobotInput[$k->kode] = (float) ($request->bobot[$k->kode] ?? 1);
        }

        $hasil = $this->smart->proses($alternatif, $kriteriaArr, $bobotInput);

        return response()->json([
            'ranked'       => $hasil['ranked'],
            'bobot_normal' => $hasil['bobotNormal'],
            'winner'       => $hasil['ranked'][0],
        ]);
    }

    // POST /api/spk/tahap2 — hitung & simpan hasil ke DB
    // Body: { "seri_id": 1, "selected_mobil": [1,2,3], "bobot": { "harga": 40, ... } }
    public function hitungTahap2(Request $request)
    {
        $request->validate([
            'seri_id'        => 'required|integer|exists:seris,id',
            'bobot'          => 'required|array',
            'bobot.*'        => 'required|numeric|min:0|max:100',
            'selected_mobil' => 'required|array|min:2',
            'winner_tahap1'  => 'nullable|array', // ranking tahap1 (opsional jika skip)
            'bobot_tahap1'   => 'nullable|array',
        ]);

        $kriteria    = DB::table('kriteria_tahap2')->orderBy('urutan')->get();
        $selectedIds = $request->selected_mobil;

        $kodeBodis = DB::table('kode_bodis')
            ->whereIn('id', $selectedIds)
            ->where('seri_id', $request->seri_id)
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

            $alternatif[] = [
                'id'    => $kb->id,
                'nama'  => $kb->nama_lengkap,
                'tahun' => $kb->tahun,
                'nilai' => $nilai,
            ];
        }

        $kriteriaArr = $kriteria->map(fn($k) => [
            'kode' => $k->kode,
            'tipe' => $k->tipe,
            'nama' => $k->nama,
        ])->toArray();

        $bobotInput = [];
        foreach ($kriteria as $k) {
            $bobotInput[$k->kode] = (float) ($request->bobot[$k->kode] ?? 0);
        }

        $hasil = $this->smart->proses($alternatif, $kriteriaArr, $bobotInput);
        $best  = $hasil['ranked'][0];

        // Simpan ke DB
        $hasilId = DB::table('hasil_analisis')->insertGetId([
            'user_id'        => Auth::id(),
            'seri_id'        => $request->seri_id,
            'kode_bodi_id'   => $best['id'],
            'bobot_tahap1'   => $request->bobot_tahap1 ? json_encode($request->bobot_tahap1) : null,
            'bobot_tahap2'   => json_encode($hasil['bobotNormal']),
            'ranking_tahap1' => $request->winner_tahap1 ? json_encode($request->winner_tahap1) : null,
            'ranking_tahap2' => json_encode($hasil['ranked']),
            'skor_akhir'     => $best['skor'],
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return response()->json([
            'hasil_id'     => $hasilId,
            'ranked'       => $hasil['ranked'],
            'bobot_normal' => $hasil['bobotNormal'],
            'winner'       => $best,
        ]);
    }

    // GET /api/riwayat — riwayat analisis user login
    public function riwayat()
    {
        $riwayat = DB::table('hasil_analisis')
            ->join('seris',      'hasil_analisis.seri_id',      '=', 'seris.id')
            ->join('kode_bodis', 'hasil_analisis.kode_bodi_id', '=', 'kode_bodis.id')
            ->where('hasil_analisis.user_id', Auth::id())
            ->select(
                'hasil_analisis.id',
                'hasil_analisis.skor_akhir',
                'hasil_analisis.created_at',
                'seris.nama as seri_nama',
                'kode_bodis.nama_lengkap as model_nama',
                'kode_bodis.gambar as model_gambar',
            )
            ->orderByDesc('hasil_analisis.created_at')
            ->get();

        return response()->json($riwayat);
    }

    // GET /api/riwayat/{id} — detail riwayat
    public function riwayatDetail($id)
    {
        $analisis = DB::table('hasil_analisis')
            ->join('seris',      'hasil_analisis.seri_id',      '=', 'seris.id')
            ->join('kode_bodis', 'hasil_analisis.kode_bodi_id', '=', 'kode_bodis.id')
            ->where('hasil_analisis.id', $id)
            ->where('hasil_analisis.user_id', Auth::id())
            ->select(
                'hasil_analisis.*',
                'seris.nama as seri_nama',
                'kode_bodis.nama_lengkap as model_nama',
                'kode_bodis.gambar as model_gambar',
            )
            ->first();

        if (!$analisis) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'id'             => $analisis->id,
            'seri_nama'      => $analisis->seri_nama,
            'model_nama'     => $analisis->model_nama,
            'model_gambar'   => $analisis->model_gambar,
            'skor_akhir'     => $analisis->skor_akhir,
            'ranking_tahap1' => json_decode($analisis->ranking_tahap1),
            'ranking_tahap2' => json_decode($analisis->ranking_tahap2),
            'bobot_tahap1'   => json_decode($analisis->bobot_tahap1),
            'bobot_tahap2'   => json_decode($analisis->bobot_tahap2),
            'created_at'     => $analisis->created_at,
        ]);
    }
}