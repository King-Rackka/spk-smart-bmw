<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\SmartService;

class SmartController extends Controller
{
    public function __construct(private SmartService $smart) {}

    // ── TAHAP 1 ─────────────────────────────────────────────────

    public function tahap1()
    {
        $kriteria = DB::table('kriteria_tahap1')->orderBy('urutan')->get();
        return view('smart.tahap1', compact('kriteria'));
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
        if (! session('tahap1_winner')) {
            return redirect()->route('spk.tahap1');
        }

        $winner  = session('tahap1_winner');
        $seri    = DB::table('seris')->find($winner['id']);
        $kriteria = DB::table('kriteria_tahap2')->orderBy('urutan')->get();

        return view('smart.tahap2', compact('winner', 'seri', 'kriteria'));
    }

    public function hitungTahap2(Request $request)
    {
        $request->validate([
            'bobot'   => 'required|array',
            'bobot.*' => 'required|numeric|min:1|max:100',
        ]);

        $winner  = session('tahap1_winner');
        if (! $winner) {
            return redirect()->route('spk.tahap1');
        }

        $kriteria  = DB::table('kriteria_tahap2')->orderBy('urutan')->get();
        $kodeBodis = DB::table('kode_bodis')->where('seri_id', $winner['id'])->get();

        $alternatif = [];
        foreach ($kodeBodis as $kb) {
            $nilaiRows = DB::table('nilai_kode_bodi')
                ->where('kode_bodi_id', $kb->id)
                ->get()
                ->keyBy('kriteria_id');

            $nilai = [];
            foreach ($kriteria as $k) {
                $nilai[$k->kode] = (float) ($nilaiRows[$k->id]->nilai ?? 0);
            }

            $alternatif[] = [
                'id'     => $kb->id,
                'nama'   => $kb->kode,
                'tahun'  => $kb->tahun,
                'nilai'  => $nilai,
            ];
        }

        $kriteriaArr = $kriteria->map(fn($k) => [
            'kode' => $k->kode,
            'tipe' => $k->tipe,
            'nama' => $k->nama,
        ])->toArray();

        $bobotInput = [];
        foreach ($kriteria as $k) {
            $bobotInput[$k->kode] = (float) ($request->bobot[$k->kode] ?? 1);
        }

        $hasil = $this->smart->proses($alternatif, $kriteriaArr, $bobotInput);

        session([
            'tahap2_hasil'    => $hasil['ranked'],
            'tahap2_bobot'    => $hasil['bobotNormal'],
            'tahap2_kriteria' => $kriteriaArr,
        ]);

        return redirect()->route('spk.hasil');
    }

    // ── HASIL ────────────────────────────────────────────────────

    public function hasil()
    {
        if (! session('tahap2_hasil')) {
            return redirect()->route('spk.tahap1');
        }

        $seri      = session('tahap1_winner');
        $seriInfo  = DB::table('seris')->find($seri['id']);
        $ranked    = session('tahap2_hasil');
        $bobot     = session('tahap2_bobot');
        $kriteria  = session('tahap2_kriteria');
        $tahap1    = session('tahap1_hasil');

        return view('smart.hasil', compact(
            'seriInfo', 'ranked', 'bobot', 'kriteria', 'tahap1'
        ));
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