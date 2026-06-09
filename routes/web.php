<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SmartController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\ProfileController;

Route::get('/', [PageController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/riwayat', [SmartController::class, 'riwayat'])->name('riwayat.index');
    Route::get('/riwayat/{id}', [SmartController::class, 'riwayatDetail'])->name('riwayat.detail');
    Route::get('/spk/pdf/{id}', [SmartController::class, 'exportPdf'])->name('spk.pdf');
});

Route::get('/mobil', [MobilController::class, 'index'])->name('mobil.index');
Route::get('/mobil/{id}', [MobilController::class, 'show'])->name('mobil.show');

// SPK
Route::prefix('spk')->name('spk.')->group(function () {
    Route::get('/tahap1', [SmartController::class, 'tahap1'])->name('tahap1');
    Route::post('/tahap1/hitung', [SmartController::class, 'hitungTahap1'])->name('tahap1.hitung');
    Route::post('/tahap1/skip', [SmartController::class, 'skipTahap1'])->name('tahap1.skip');
    Route::get('/tahap2', [SmartController::class, 'tahap2'])->name('tahap2');
    Route::post('/tahap2/hitung', [SmartController::class, 'hitungTahap2'])->name('tahap2.hitung');
    Route::get('/hasil', [SmartController::class, 'hasil'])->name('hasil');
    Route::get('/reset', [SmartController::class, 'reset'])->name('reset');
});

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/',                           [AdminController::class, 'dashboard'])->name('dashboard');
 
    // Mobil CRUD
    Route::get('/mobil',                      [AdminController::class, 'mobilIndex'])->name('mobil');
    Route::get('/mobil/create',               [AdminController::class, 'mobilCreate'])->name('mobil.create');
    Route::post('/mobil',                     [AdminController::class, 'mobilStore'])->name('mobil.store');
    Route::get('/mobil/{id}/edit',            [AdminController::class, 'mobilEdit'])->name('mobil.edit');
    Route::put('/mobil/{id}',                 [AdminController::class, 'mobilUpdate'])->name('mobil.update');
    Route::delete('/mobil/{id}',              [AdminController::class, 'mobilDestroy'])->name('mobil.destroy');
 
    // Seri
    Route::get('/seri',                       [AdminController::class, 'seriIndex'])->name('seri');
    Route::put('/seri/{id}', [AdminController::class, 'seriUpdateAll'])->name('seri.updateAll');
    Route::post('/seri',              [AdminController::class, 'seriStore'])->name('seri.store');
    // Kriteria
    Route::get('/kriteria',                   [AdminController::class, 'kriteriaIndex'])->name('kriteria');
    Route::put('/kriteria/{tahap}/{id}',      [AdminController::class, 'kriteriaUpdate'])->name('kriteria.update');
 
    // Riwayat semua user
    Route::get('/riwayat',                    [AdminController::class, 'riwayatIndex'])->name('riwayat');
 
    // User management
    Route::get('/user',                       [AdminController::class, 'userIndex'])->name('user');
    Route::patch('/user/{id}/role',           [AdminController::class, 'userToggleRole'])->name('user.role');
    Route::delete('/user/{id}',               [AdminController::class, 'userDestroy'])->name('user.destroy');
});

require __DIR__.'/auth.php';