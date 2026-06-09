<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;



class AdminController extends Controller
{
    // ── MIDDLEWARE CHECK ─────────────────────────────────────────
    private function checkAdmin()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }
    }

    // ── DASHBOARD ────────────────────────────────────────────────
    public function dashboard()
    {
        $this->checkAdmin();

        $stats = [
            'total_user'    => DB::table('users')->where('role', 'user')->count(),
            'total_analisis'=> DB::table('hasil_analisis')->count(),
            'total_mobil'   => DB::table('kode_bodis')->count(),
            'total_seri'    => DB::table('seris')->count(),
        ];

        // Analisis per bulan (6 bulan terakhir)
        $analisisPerBulan = DB::table('hasil_analisis')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as bulan, COUNT(*) as total")
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Mobil paling sering direkomendasikan
        $topMobil = DB::table('hasil_analisis')
            ->join('kode_bodis', 'hasil_analisis.kode_bodi_id', '=', 'kode_bodis.id')
            ->join('seris', 'kode_bodis.seri_id', '=', 'seris.id')
            ->selectRaw('kode_bodis.nama_lengkap, seris.nama as seri_nama, COUNT(*) as total')
            ->groupBy('kode_bodis.id', 'kode_bodis.nama_lengkap', 'seris.nama')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Seri paling banyak dianalisis
        $topSeri = DB::table('hasil_analisis')
            ->join('seris', 'hasil_analisis.seri_id', '=', 'seris.id')
            ->selectRaw('seris.nama, COUNT(*) as total')
            ->groupBy('seris.id', 'seris.nama')
            ->orderByDesc('total')
            ->get();

        // Analisis terbaru
        $analisisTerbaru = DB::table('hasil_analisis')
            ->join('users', 'hasil_analisis.user_id', '=', 'users.id')
            ->join('seris', 'hasil_analisis.seri_id', '=', 'seris.id')
            ->join('kode_bodis', 'hasil_analisis.kode_bodi_id', '=', 'kode_bodis.id')
            ->select(
                'hasil_analisis.id',
                'hasil_analisis.skor_akhir',
                'hasil_analisis.created_at',
                'users.name as user_name',
                'seris.nama as seri_nama',
                'kode_bodis.nama_lengkap as model_nama'
            )
            ->orderByDesc('hasil_analisis.created_at')
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'analisisPerBulan', 'topMobil', 'topSeri', 'analisisTerbaru'
        ));
    }

    // ── KELOLA MOBIL (CRUD) ──────────────────────────────────────
    public function mobilIndex()
    {
        $this->checkAdmin();

        $seris  = DB::table('seris')->orderBy('id')->get();
        $filter = request('seri');

        $query = DB::table('kode_bodis')
            ->join('seris', 'kode_bodis.seri_id', '=', 'seris.id')
            ->select('kode_bodis.*', 'seris.nama as seri_nama');

        if ($filter) {
            $query->where('kode_bodis.seri_id', $filter);
        }

        $mobils = $query->orderBy('seris.id')->orderBy('kode_bodis.nama_lengkap')->paginate(15);

        return view('admin.mobil.index', compact('seris', 'mobils', 'filter'));
    }

    public function mobilCreate()
    {
        $this->checkAdmin();
        $seris    = DB::table('seris')->orderBy('id')->get();
        $kriteria = DB::table('kriteria_tahap2')->orderBy('urutan')->get();
        return view('admin.mobil.create', compact('seris', 'kriteria'));
    }

    public function mobilStore(Request $request)
{
    $this->checkAdmin();
 
    $request->validate([
        'seri_id'      => 'required|exists:seris,id',
        'kode'         => 'required|string|max:20',
        'nama_lengkap' => 'required|string|max:100',
        'tahun'        => 'required|string|max:20',
        'deskripsi'    => 'nullable|string',
        'gambar'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'nilai'        => 'required|array',
        'nilai.*'      => 'required|numeric|min:0',
    ]);
 
    // Handle upload gambar
    $namaGambar = null;
    if ($request->hasFile('gambar')) {
        $file       = $request->file('gambar');
        $namaGambar = time() . '_' . \Illuminate\Support\Str::slug($request->nama_lengkap) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/mobil'), $namaGambar);
    }
 
    $id = DB::table('kode_bodis')->insertGetId([
        'seri_id'      => $request->seri_id,
        'kode'         => strtoupper($request->kode),
        'nama_lengkap' => $request->nama_lengkap,
        'tahun'        => $request->tahun,
        'deskripsi'    => $request->deskripsi,
        'gambar'       => $namaGambar,
        'created_at'   => now(),
        'updated_at'   => now(),
    ]);
 
    // Simpan nilai kriteria
    $kriteria = DB::table('kriteria_tahap2')->orderBy('urutan')->get();
    foreach ($kriteria as $k) {
        DB::table('nilai_kode_bodi')->insert([
            'kode_bodi_id' => $id,
            'kriteria_id'  => $k->id,
            'nilai'        => $request->nilai[$k->kode] ?? 0,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
    }
 
    return redirect()->route('admin.mobil')->with('success', "Mobil {$request->nama_lengkap} berhasil ditambahkan.");
}   

    public function mobilEdit($id)
    {
        $this->checkAdmin();

        $mobil    = DB::table('kode_bodis')->find($id);
        $seris    = DB::table('seris')->orderBy('id')->get();
        $kriteria = DB::table('kriteria_tahap2')->orderBy('urutan')->get();
        $nilaiMap = DB::table('nilai_kode_bodi')
            ->where('kode_bodi_id', $id)
            ->get()
            ->keyBy('kriteria_id');

        return view('admin.mobil.edit', compact('mobil', 'seris', 'kriteria', 'nilaiMap'));
    }

    public function mobilUpdate(Request $request, $id)
{
    $this->checkAdmin();
 
    $request->validate([
        'seri_id'      => 'required|exists:seris,id',
        'kode'         => 'required|string|max:20',
        'nama_lengkap' => 'required|string|max:100',
        'tahun'        => 'required|string|max:20',
        'deskripsi'    => 'nullable|string',
        'gambar'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'nilai'        => 'required|array',
        'nilai.*'      => 'required|numeric|min:0',
    ]);
 
    $mobil = DB::table('kode_bodis')->find($id);
 
    // Handle upload gambar baru
    $namaGambar = $mobil->gambar; // default: tetap pakai yang lama
    if ($request->hasFile('gambar')) {
        // Hapus file lama kalau ada
        if ($mobil->gambar && file_exists(public_path('images/mobil/' . $mobil->gambar))) {
            unlink(public_path('images/mobil/' . $mobil->gambar));
        }
        $file       = $request->file('gambar');
        $namaGambar = time() . '_' . \Illuminate\Support\Str::slug($request->nama_lengkap) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/mobil'), $namaGambar);
    }
 
    DB::table('kode_bodis')->where('id', $id)->update([
        'seri_id'      => $request->seri_id,
        'kode'         => strtoupper($request->kode),
        'nama_lengkap' => $request->nama_lengkap,
        'tahun'        => $request->tahun,
        'deskripsi'    => $request->deskripsi,
        'gambar'       => $namaGambar,
        'updated_at'   => now(),
    ]);
 
    // Update nilai kriteria
    $kriteria = DB::table('kriteria_tahap2')->orderBy('urutan')->get();
    foreach ($kriteria as $k) {
        DB::table('nilai_kode_bodi')->updateOrInsert(
            ['kode_bodi_id' => $id, 'kriteria_id' => $k->id],
            ['nilai' => $request->nilai[$k->kode] ?? 0, 'updated_at' => now()]
        );
    }
 
    return redirect()->route('admin.mobil')->with('success', "Data mobil berhasil diperbarui.");
}

    public function mobilDestroy($id)
    {
        $this->checkAdmin();
        $mobil = DB::table('kode_bodis')->find($id);
        DB::table('kode_bodis')->delete($id);
        return redirect()->route('admin.mobil')->with('success', "Mobil {$mobil->nama_lengkap} berhasil dihapus.");
    }

    // ── KELOLA KRITERIA ──────────────────────────────────────────
    public function kriteriaIndex()
    {
        $this->checkAdmin();
        $tahap1 = DB::table('kriteria_tahap1')->orderBy('urutan')->get();
        $tahap2 = DB::table('kriteria_tahap2')->orderBy('urutan')->get();
        return view('admin.kriteria.index', compact('tahap1', 'tahap2'));
    }

    public function kriteriaUpdate(Request $request, $tahap, $id)
    {
        $this->checkAdmin();
        $table = $tahap === '1' ? 'kriteria_tahap1' : 'kriteria_tahap2';

        $request->validate([
            'nama'      => 'required|string|max:100',
            'pertanyaan'=> 'required|string',
            'tipe'      => 'required|in:benefit,cost',
            'urutan'    => 'required|integer|min:1',
        ]);

        DB::table($table)->where('id', $id)->update([
            'nama'       => $request->nama,
            'pertanyaan' => $request->pertanyaan,
            'tipe'       => $request->tipe,
            'urutan'     => $request->urutan,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.kriteria')->with('success', 'Kriteria berhasil diperbarui.');
    }

    // ── KELOLA SERI ──────────────────────────────────────────────
    public function seriIndex()
{
    $this->checkAdmin();
 
    $seris = DB::table('seris')
        ->leftJoin('kode_bodis', 'seris.id', '=', 'kode_bodis.seri_id')
        ->selectRaw('seris.*, COUNT(kode_bodis.id) as jumlah_model')
        ->groupBy('seris.id', 'seris.nama', 'seris.slug', 'seris.deskripsi', 'seris.created_at', 'seris.updated_at')
        ->orderBy('seris.id')
        ->get();
 
    // Kriteria tahap 1
    $kriteria = DB::table('kriteria_tahap1')->orderBy('urutan')->get();
 
    // Nilai per seri: [seri_id][kriteria_id] => row
    $nilaiMap = [];
    foreach ($seris as $s) {
        $rows = DB::table('nilai_seri')
            ->where('seri_id', $s->id)
            ->get()
            ->keyBy('kriteria_id');
        $nilaiMap[$s->id] = $rows;
    }
 
    return view('admin.seri.index', compact('seris', 'kriteria', 'nilaiMap'));
}

public function seriStore(Request $request)
{
    $this->checkAdmin();
 
    $request->validate([
        'nama'      => 'required|string|max:100',
        'slug'      => 'required|string|max:50|unique:seris,slug|regex:/^[a-z0-9\-]+$/',
        'deskripsi' => 'nullable|string',
    ]);
 
    DB::table('seris')->insert([
        'nama'       => $request->nama,
        'slug'       => $request->slug,
        'deskripsi'  => $request->deskripsi,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
 
    return redirect()->route('admin.seri')->with('success', "Seri {$request->nama} berhasil ditambahkan.");
}

public function seriUpdateAll(Request $request, $id)
{
    $this->checkAdmin();
 
    $request->validate([
        'nama'    => 'required|string|max:100',
        'deskripsi' => 'nullable|string',
        'nilai'   => 'required|array',
        'nilai.*' => 'required|numeric|min:1|max:5',
    ]);
 
    // Update info seri
    DB::table('seris')->where('id', $id)->update([
        'nama'       => $request->nama,
        'deskripsi'  => $request->deskripsi,
        'updated_at' => now(),
    ]);
 
    // Update nilai kriteria tahap 1 sekaligus
    $kriteria = DB::table('kriteria_tahap1')->get();
    foreach ($kriteria as $k) {
        $val = $request->nilai[$k->kode] ?? 3;
        DB::table('nilai_seri')->updateOrInsert(
            ['seri_id' => $id, 'kriteria_id' => $k->id],
            ['nilai' => $val, 'updated_at' => now()]
        );
    }
 
    return redirect()->route('admin.seri')->with('success', 'Seri dan nilai kriteria berhasil diperbarui.');
}
 
    // ── RIWAYAT SEMUA USER ───────────────────────────────────────
    public function riwayatIndex()
    {
        $this->checkAdmin();

        $query = DB::table('hasil_analisis')
            ->join('users', 'hasil_analisis.user_id', '=', 'users.id')
            ->join('seris', 'hasil_analisis.seri_id', '=', 'seris.id')
            ->join('kode_bodis', 'hasil_analisis.kode_bodi_id', '=', 'kode_bodis.id')
            ->select(
                'hasil_analisis.id',
                'hasil_analisis.skor_akhir',
                'hasil_analisis.created_at',
                'users.name as user_name',
                'users.email as user_email',
                'seris.nama as seri_nama',
                'kode_bodis.nama_lengkap as model_nama'
            );

        if (request('user')) {
            $query->where('users.name', 'like', '%' . request('user') . '%');
        }

        $riwayat = $query->orderByDesc('hasil_analisis.created_at')->paginate(20);

        return view('admin.riwayat', compact('riwayat'));
    }

    // ── KELOLA USER ──────────────────────────────────────────────
    public function userIndex()
    {
        $this->checkAdmin();
        $users = DB::table('users')->orderByDesc('created_at')->paginate(20);
        return view('admin.user', compact('users'));
    }

    public function userToggleRole($id)
    {
        $this->checkAdmin();
        $user    = DB::table('users')->find($id);
        $newRole = $user->role === 'admin' ? 'user' : 'admin';
        DB::table('users')->where('id', $id)->update(['role' => $newRole]);
        return redirect()->route('admin.user')->with('success', "Role {$user->name} diubah ke {$newRole}.");
    }

    public function userDestroy($id)
    {
        $this->checkAdmin();
        if ((int)$id === (int)Auth::id()) {
            return back()->with('error', 'Tidak bisa hapus akun sendiri.');
        }
        $user = DB::table('users')->find($id);
        DB::table('users')->delete($id);
        return redirect()->route('admin.user')->with('success', "User {$user->name} berhasil dihapus.");
    }
}