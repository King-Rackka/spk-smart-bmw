<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    public function index()
    {
        $stats = [
            'total_model'    => DB::table('kode_bodis')->count(),
            'total_kriteria' => DB::table('kriteria_tahap2')->count(),
            'total_seri'     => DB::table('seris')->count(),
        ];

        return view('home', compact('stats'));
    }
}