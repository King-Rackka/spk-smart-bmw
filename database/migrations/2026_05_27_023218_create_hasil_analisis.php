<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hasil_analisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('seri_id')->constrained('seris')->onDelete('cascade');
            $table->foreignId('kode_bodi_id')->constrained('kode_bodis')->onDelete('cascade');
            $table->json('bobot_tahap1');       // bobot input user tahap 1
            $table->json('bobot_tahap2');       // bobot input user tahap 2
            $table->json('ranking_tahap1');     // snapshot ranking semua seri
            $table->json('ranking_tahap2');     // snapshot ranking semua kode bodi
            $table->decimal('skor_akhir', 8, 6); // skor SMART pemenang (0-1)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_analisis');
    }
};