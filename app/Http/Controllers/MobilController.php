<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class MobilController extends Controller
{
    public function index()
    {
        $seris = DB::table('seris')->orderBy('id')->get();

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

        // Paginate 6 per halaman, pertahankan query string seri
        $mobils = $query->paginate(6)->withQueryString();

        return view('mobil.index', compact('seris', 'mobils'));
    }

    public function show($id)
    {
        $mobil = DB::table('kode_bodis')
            ->join('seris', 'kode_bodis.seri_id', '=', 'seris.id')
            ->select('kode_bodis.*', 'seris.nama as seri_nama', 'seris.slug as seri_slug')
            ->where('kode_bodis.id', $id)
            ->firstOrFail();

        $seri = DB::table('seris')->find($mobil->seri_id);

        $nilaiKriteria = DB::table('nilai_kode_bodi')
            ->join('kriteria_tahap2', 'nilai_kode_bodi.kriteria_id', '=', 'kriteria_tahap2.id')
            ->select(
                'kriteria_tahap2.nama as nama_kriteria',
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

        return view('mobil.show', compact('mobil', 'seri', 'nilaiKriteria', 'mobilLain'));
    }
}