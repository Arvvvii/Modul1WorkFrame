<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // disable foreign key checks, truncate tables to avoid duplicates
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('buku')->truncate();
        DB::table('kategori')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // insert categories
        $novelId = DB::table('kategori')->insertGetId(['nama_kategori' => 'Novel']);
        $bioId = DB::table('kategori')->insertGetId(['nama_kategori' => 'Biografi']);
        $komikId = DB::table('kategori')->insertGetId(['nama_kategori' => 'Komik']);

        // insert books
        DB::table('buku')->insert([
            [
                'kode' => 'BK001',
                'judul' => 'Home Sweet Loan',
                'pengarang' => 'Almira Bastari',
                'idkategori' => $novelId,
            ],
            [
                'kode' => 'BK002',
                'judul' => 'Mohammad Hatta, Untuk Negeriku',
                'pengarang' => 'Taufik Abdullah',
                'idkategori' => $bioId,
            ],
            [
                'kode' => 'BK003',
                'judul' => 'Keajaiban Toko Kelontong Namiya',
                'pengarang' => 'Keigo Higashino',
                'idkategori' => $novelId,
            ],
        ]);
    }
}
