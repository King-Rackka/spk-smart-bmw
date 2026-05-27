<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tabel seri BMW (Seri 3, Seri 5, Seri X)
        Schema::create('seris', function (Blueprint $table) {
            $table->id();
            $table->string('nama');         // "BMW Seri 3"
            $table->string('slug');         // "seri3"
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // Tabel kode bodi (E90, F30, F10, dst)
        Schema::create('kode_bodis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seri_id')->constrained('seris')->onDelete('cascade');
            $table->string('kode');         // "F30"
            $table->string('tahun');        // "2012-2018"
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // Tabel kriteria tahap 1 (gaya hidup)
        Schema::create('kriteria_tahap1', function (Blueprint $table) {
            $table->id();
            $table->string('nama');         // "Kapasitas & Akomodasi"
            $table->string('kode');         // "kapasitas"
            $table->enum('tipe', ['benefit', 'cost']);
            $table->text('pertanyaan');     // pertanyaan untuk user
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // Tabel kriteria tahap 2 (teknis & finansial)
        Schema::create('kriteria_tahap2', function (Blueprint $table) {
            $table->id();
            $table->string('nama');         // "Harga Beli"
            $table->string('kode');         // "harga"
            $table->enum('tipe', ['benefit', 'cost']);
            $table->text('pertanyaan');
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // Nilai alternatif seri untuk tahap 1
        // Nilai NYATA, bukan skor — sistem hitung utility sendiri
        Schema::create('nilai_seri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seri_id')->constrained('seris')->onDelete('cascade');
            $table->foreignId('kriteria_id')->constrained('kriteria_tahap1')->onDelete('cascade');
            $table->decimal('nilai', 10, 2); // nilai nyata
            $table->timestamps();

            $table->unique(['seri_id', 'kriteria_id']);
        });

        // Nilai alternatif kode bodi untuk tahap 2
        Schema::create('nilai_kode_bodi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kode_bodi_id')->constrained('kode_bodis')->onDelete('cascade');
            $table->foreignId('kriteria_id')->constrained('kriteria_tahap2')->onDelete('cascade');
            $table->decimal('nilai', 10, 2); // nilai nyata (harga dalam juta, skor 1-5, dll)
            $table->timestamps();

            $table->unique(['kode_bodi_id', 'kriteria_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_kode_bodi');
        Schema::dropIfExists('nilai_seri');
        Schema::dropIfExists('kriteria_tahap2');
        Schema::dropIfExists('kriteria_tahap1');
        Schema::dropIfExists('kode_bodis');
        Schema::dropIfExists('seris');
    }
};