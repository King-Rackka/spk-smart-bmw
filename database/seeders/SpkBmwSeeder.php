<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SpkBmwSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('nilai_kode_bodi')->delete();
        DB::table('nilai_seri')->delete();
        DB::table('kode_bodis')->delete();
        DB::table('kriteria_tahap2')->delete();
        DB::table('kriteria_tahap1')->delete();
        DB::table('seris')->delete();
        DB::table('users')->delete();

        // ── USERS ───────────────────────────────────────────────
        DB::table('users')->insert([
            [
                'id'         => 1,
                'name'       => 'Admin',
                'email'      => 'admin@gmail.com',
                'role'       => 'admin',
                'password'   => Hash::make('admin123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 2,
                'name'       => 'Budi',
                'email'      => 'user@gmail.com',
                'role'       => 'user',
                'password'   => Hash::make('user123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ── SERI ────────────────────────────────────────────────
        DB::table('seris')->insert([
            ['id' => 1, 'nama' => 'BMW Seri 2', 'slug' => 'seri-2', 'deskripsi' => 'Model berkarakter sporty tersedia dalam varian Coupé dan Gran Coupé.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama' => 'BMW Seri 3', 'slug' => 'seri-3', 'deskripsi' => 'Sedan kompak legendaris yang menjadi ikon performa BMW, fun-to-drive sejati.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama' => 'BMW Seri 4', 'slug' => 'seri-4', 'deskripsi' => 'Versi sporty dari Seri 3, tersedia dalam bentuk Coupé dan Gran Coupé.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nama' => 'BMW Seri 5', 'slug' => 'seri-5', 'deskripsi' => 'Sedan eksekutif kelas menengah yang menawarkan kenyamanan dan teknologi tinggi.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nama' => 'BMW Seri 7', 'slug' => 'seri-7', 'deskripsi' => 'Sedan flagship termewah dan paling prestisius dari BMW.', 'created_at' => now(), 'updated_at' => now()],
        ]);


        // ── KRITERIA TAHAP 1 ────────────────────────────────────
        DB::table('kriteria_tahap1')->insert([
            [
                'id'=>1, 
                'nama'=>'Fun to Drive', 
                'kode'=>'fun_to_drive', 
                'tipe'=>'benefit', 
                'pertanyaan' => 'Seberapa penting sensasi berkendara yang sporty, responsif, dan menyenangkan bagi Anda?',                'urutan'=>1, 
                'created_at'=>now(), 
                'updated_at'=>now()],
            [
                'id'=>2, 
                'nama'=>'Gengsi & Image Sosial',        
                'kode'=>'gengsi',    
                'tipe'=>'benefit', 
                'pertanyaan'=>'Seberapa penting kesan dan aura status sosial yang ditampilkan oleh seri BMW pilihan Anda?',                
                'urutan'=>2, 
                'created_at'=>now(), 
                'updated_at'=>now()],
            [
                'id'=>3, 
                'nama'=>'Kenyamanan Kabin',
                'kode'=>'kenyamanan', 
                'tipe'=>'benefit', 
                'pertanyaan'=>'Seberapa penting kenyamanan kabin — ruang kaki, keempukan jok, dan ketenangan interior?',                     
                'urutan'=>3, 
                'created_at'=>now(), 
                'updated_at'=>now()],
            [
                'id'=>4, 
                'nama'=>'Fungsionalitas Harian',
                'kode'=>'fungsionalitas', 
                'tipe'=>'benefit', 
                'pertanyaan'=>'Seberapa penting kemudahan parkir, kapasitas bagasi, dan kepraktisan untuk pemakaian kota sehari-hari?',             
                'urutan'=>4, 
                'created_at'=>now(), 
                'updated_at'=>now()],

            [
                'id'=>5, 
                'nama'=>'Durabilitas & Keandalan', 
                'kode'=>'durabilitas', 
                'tipe'=>'benefit', 
                'pertanyaan'=>'Seberapa penting ketahanan dan keandalan jangka panjang untuk pemakaian intensif?',             
                'urutan'=>5, 
                'created_at'=>now(), 
                'updated_at'=>now()
            ]
        ]);

        // ── KRITERIA TAHAP 2 ────────────────────────────────────
        DB::table('kriteria_tahap2')->insert([
            [
                'id'=>1, 
                'nama'=>'Harga Pasar Kendaraan', 
                'kode'=>'harga',
                'tipe'=>'cost',
                'pertanyaan'=>'Seberapa penting harga beli yang terjangkau sesuai budget Anda?',                              
                'urutan'=>1, 
                'created_at'=>now(), 
                'updated_at'=>now()],
            [
                'id'=>2, 
                'nama'=>'Biaya Perawatan & Servis Rutin', 
                'kode'=>'rawat', 
                'tipe'=>'cost',
                'pertanyaan'=>'Seberapa penting biaya servis rutin dan perawatan tahunan yang murah?',                      
                'urutan'=>2, 
                'created_at'=>now(), 
                'updated_at'=>now()],
            [
                'id'=>3, 
                'nama'=>'Ketersediaan Suku Cadang', 
                'kode'=>'sperpart', 
                'tipe'=>'benefit', 
                'pertanyaan'=>'Seberapa penting kemudahan mendapatkan suku cadang di pasaran lokal?',        
                'urutan'=>3, 
                'created_at'=>now(), 
                'updated_at'=>now()],
            [
                'id'=>4, 
                'nama'=>'Durabilitas Jangka Panjang', 
                'kode'=>'durabilitas', 
                'tipe'=>'benefit', 
                'pertanyaan'=>'Seberapa penting ketahanan komponen untuk pemakaian harian jangka panjang?',       
                'urutan'=>4, 
                'created_at'=>now(), 
                'updated_at'=>now()],
            [
                'id'=>5, 
                'nama'=>'Ketersedian Bahan Bakar', 
                'kode'=>'bahan_bakar', 
                'tipe'=>'benefit', 
                'pertanyaan'=>'Seberapa penting ketersediaan jenis bahan bakar yang mudah didapat dan efisien (bensin, diesel, atau hybrid/listrik)?',              
                'urutan'=>5, 
                'created_at'=>now(), 
                'updated_at'=>now()],
        ]);

        DB::table('nilai_seri')->insert([
            // Seri 2
            ['seri_id'=>1,'kriteria_id'=>1,'nilai'=>2,'created_at'=>now(),'updated_at'=>now()],
            ['seri_id'=>1,'kriteria_id'=>2,'nilai'=>2,'created_at'=>now(),'updated_at'=>now()],
            ['seri_id'=>1,'kriteria_id'=>3,'nilai'=>2,'created_at'=>now(),'updated_at'=>now()],
            ['seri_id'=>1,'kriteria_id'=>4,'nilai'=>3,'created_at'=>now(),'updated_at'=>now()],
            ['seri_id'=>1,'kriteria_id'=>5,'nilai'=>4,'created_at'=>now(),'updated_at'=>now()],
            // Seri 3 
            ['seri_id'=>2,'kriteria_id'=>1,'nilai'=>5,'created_at'=>now(),'updated_at'=>now()],
            ['seri_id'=>2,'kriteria_id'=>2,'nilai'=>4,'created_at'=>now(),'updated_at'=>now()],
            ['seri_id'=>2,'kriteria_id'=>3,'nilai'=>4,'created_at'=>now(),'updated_at'=>now()],
            ['seri_id'=>2,'kriteria_id'=>4,'nilai'=>4,'created_at'=>now(),'updated_at'=>now()],
            ['seri_id'=>2,'kriteria_id'=>5,'nilai'=>4,'created_at'=>now(),'updated_at'=>now()], 
            // Seri 4
            ['seri_id'=>3,'kriteria_id'=>1,'nilai'=>5,'created_at'=>now(),'updated_at'=>now()],
            ['seri_id'=>3,'kriteria_id'=>2,'nilai'=>4,'created_at'=>now(),'updated_at'=>now()],
            ['seri_id'=>3,'kriteria_id'=>3,'nilai'=>4,'created_at'=>now(),'updated_at'=>now()],
            ['seri_id'=>3,'kriteria_id'=>4,'nilai'=>4,'created_at'=>now(),'updated_at'=>now()],
            ['seri_id'=>3,'kriteria_id'=>5,'nilai'=>3,'created_at'=>now(),'updated_at'=>now()],
            // Seri 5 
            ['seri_id'=>4,'kriteria_id'=>1,'nilai'=>4,'created_at'=>now(),'updated_at'=>now()],
            ['seri_id'=>4,'kriteria_id'=>2,'nilai'=>5,'created_at'=>now(),'updated_at'=>now()],
            ['seri_id'=>4,'kriteria_id'=>3,'nilai'=>5,'created_at'=>now(),'updated_at'=>now()],
            ['seri_id'=>4,'kriteria_id'=>4,'nilai'=>5,'created_at'=>now(),'updated_at'=>now()],
            ['seri_id'=>4,'kriteria_id'=>5,'nilai'=>3,'created_at'=>now(),'updated_at'=>now()],
            // Seri 7 
            ['seri_id'=>5,'kriteria_id'=>1,'nilai'=>3,'created_at'=>now(),'updated_at'=>now()],
            ['seri_id'=>5,'kriteria_id'=>2,'nilai'=>5,'created_at'=>now(),'updated_at'=>now()],
            ['seri_id'=>5,'kriteria_id'=>3,'nilai'=>5,'created_at'=>now(),'updated_at'=>now()],
            ['seri_id'=>5,'kriteria_id'=>4,'nilai'=>5,'created_at'=>now(),'updated_at'=>now()],
            ['seri_id'=>5,'kriteria_id'=>5,'nilai'=>4,'created_at'=>now(),'updated_at'=>now()],
        ]);

    }
}