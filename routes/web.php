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

require __DIR__.'/auth.php';