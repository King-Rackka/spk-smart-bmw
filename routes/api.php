<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\MobilApiController;
use App\Http\Controllers\Api\SpkApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json(['message' => 'API berhasil!']);
});

// Auth
Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/register', [AuthApiController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/me', [AuthApiController::class, 'me']);

    // Data Mobil
    Route::get('/seri', [MobilApiController::class, 'seri']);
    Route::get('/mobil', [MobilApiController::class, 'index']);
    Route::get('/mobil/{id}', [MobilApiController::class, 'show']);

    // SPK
    Route::get('/kriteria/tahap1', [SpkApiController::class, 'kriteriaTahap1']);
    Route::get('/kriteria/tahap2', [SpkApiController::class, 'kriteriaTahap2']);
    Route::post('/spk/tahap1', [SpkApiController::class, 'hitungTahap1']);
    Route::post('/spk/tahap2', [SpkApiController::class, 'hitungTahap2']);
    Route::get('/riwayat', [SpkApiController::class, 'riwayat']);
    Route::get('/riwayat/{id}', [SpkApiController::class, 'riwayatDetail']);
});