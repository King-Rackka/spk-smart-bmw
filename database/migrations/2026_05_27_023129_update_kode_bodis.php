<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('kode_bodis', function (Blueprint $table) {
            $table->string('nama_lengkap')->after('kode');
            $table->string('gambar')->nullable()->after('deskripsi');
            $table->enum('transmisi', ['automatic', 'manual'])->default('automatic')->after('gambar');
            $table->enum('tipe_bodi', ['sedan', 'hatchback', 'coupe', 'suv', 'gran_coupe'])->after('transmisi');
            $table->enum('bahan_bakar', ['bensin', 'listrik', 'hybrid'])->default('bensin')->after('tipe_bodi');
        });
    }

    public function down(): void
    {
        Schema::table('kode_bodis', function (Blueprint $table) {
            $table->dropColumn(['nama_lengkap', 'gambar', 'transmisi', 'tipe_bodi', 'bahan_bakar']);
        });
    }
};