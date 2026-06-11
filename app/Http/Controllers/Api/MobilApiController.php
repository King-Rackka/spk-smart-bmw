<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MobilApiController extends Controller
{
    // GET /api/seri — list semua seri BMW
    public function seri()
    {
        $seris = DB::table('seris')->orderBy('id')->get();
        return response()->json($seris);
    }

    // GET /api/mobil?seri=seri3 — list kode bodi (bisa filter by seri)
    public function index()
    {
        $query = DB::table('kode_bodis')
            ->join('seris', 'kode_bodis.seri_id', '=', 'seris.id')
            ->select(
                'kode_bodis.*',
                'seris.nama as seri_nama',
                'seris.slug as seri_slug'
            )
            ->orderBy('seris.id')
            ->orderBy('kode_bodis.nama_lengkap');

        if (request('seri')) {
            $query->where('seris.slug', request('seri'));
        }

        $mobils = $query->get();
        return response()->json($mobils);
    }

    // GET /api/mobil/{id} — detail satu mobil beserta nilai kriterianya
    public function show($id)
    {
        $mobil = DB::table('kode_bodis')
            ->join('seris', 'kode_bodis.seri_id', '=', 'seris.id')
            ->select('kode_bodis.*', 'seris.nama as seri_nama', 'seris.slug as seri_slug')
            ->where('kode_bodis.id', $id)
            ->first();

        if (!$mobil) {
            return response()->json(['message' => 'Mobil tidak ditemukan'], 404);
        }

        $nilaiKriteria = DB::table('nilai_kode_bodi')
            ->join('kriteria_tahap2', 'nilai_kode_bodi.kriteria_id', '=', 'kriteria_tahap2.id')
            ->select(
                'kriteria_tahap2.nama as nama_kriteria',
                'kriteria_tahap2.kode',
                'kriteria_tahap2.tipe',
                'nilai_kode_bodi.nilai'
            )
            ->where('nilai_kode_bodi.kode_bodi_id', $id)
            ->orderBy('kriteria_tahap2.urutan')
            ->get();

        $mobilLain = DB::table('kode_bodis')
            ->where('seri_id', $mobil->seri_id)
            ->where('id', '!=', $id)
            ->limit(4)
            ->get();

        return response()->json([
            'mobil'          => $mobil,
            'nilai_kriteria' => $nilaiKriteria,
            'mobil_lain'     => $mobilLain,
        ]);
    }
}