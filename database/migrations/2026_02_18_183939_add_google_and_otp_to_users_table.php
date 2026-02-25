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
        // Ensure `email` column exists (default Laravel users table has it)
        if (! Schema::hasColumn('users', 'email')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('email', 255)->nullable()->unique()->after('name');
            });
        }

        Schema::table('users', function (Blueprint $table) {
            // Google account id (nullable)
            if (! Schema::hasColumn('users', 'id_google')) {
                $table->string('id_google', 256)->nullable()->after('remember_token');
            }
            // One-time password / verification code
            if (! Schema::hasColumn('users', 'otp')) {
                $table->string('otp', 6)->nullable()->after('id_google');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop columns if they exist
            if (Schema::hasColumn('users', 'otp')) {
                $table->dropColumn('otp');
            }
            if (Schema::hasColumn('users', 'id_google')) {
                $table->dropColumn('id_google');
            }
        });
    }
};
