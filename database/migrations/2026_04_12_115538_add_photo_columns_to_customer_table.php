<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ganti 'customer' jadi 'customers' sesuai namatabelmu
        Schema::table('customers', function (Blueprint $table) {
            $table->longText('foto_blob')->nullable(); // Simpan data gambar biner
            $table->string('foto_path')->nullable(); // Simpan alamat file gambar
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Hapus kolom jika migration di-rollback
            $table->dropColumn(['foto_blob', 'foto_path']);
        });
    }
};