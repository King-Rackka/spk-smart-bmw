<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SmartController;
use App\Http\Controllers\AdminController;

Route::get('/', [PageController::class, 'index'])->name('home');

Route::prefix('spk')->name('spk.')->group(function () {
    Route::get('/tahap1',            [SmartController::class, 'tahap1'])->name('tahap1');
    Route::post('/tahap1/hitung',    [SmartController::class, 'hitungTahap1'])->name('tahap1.hitung');
    Route::get('/tahap2',            [SmartController::class, 'tahap2'])->name('tahap2');
    Route::post('/tahap2/hitung',    [SmartController::class, 'hitungTahap2'])->name('tahap2.hitung');
    Route::get('/hasil',             [SmartController::class, 'hasil'])->name('hasil');
    Route::get('/reset',             [SmartController::class, 'reset'])->name('reset');
});

// Route::prefix('admin')->name('admin.')->group(function () {
//     Route::resource('seri',             AdminController::class . '@seri');
//     Route::resource('kode-bodi',        AdminController::class . '@kodeBodi');
//     Route::resource('kriteria',         AdminController::class . '@kriteria');
//     Route::resource('nilai-alternatif', AdminController::class . '@nilaiAlternatif');
// });