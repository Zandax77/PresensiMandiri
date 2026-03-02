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
        Schema::table('sekolahs', function (Blueprint $table) {
            // Remove old single time fields
            $table->dropColumn([
                'jam_datang_mulai',
                'jam_datang_akhir',
                'jam_pulang_mulai',
                'jam_pulang_akhir'
            ]);

            // Add JSON field for day-specific times
            $table->json('jam_presensi')->nullable()->after('email');
        });

        Schema::table('liburs', function (Blueprint $table) {
            // Change tanggal to tanggal_mulai and add tanggal_akhir
            $table->dropUnique('liburs_tanggal_unique');
            $table->renameColumn('tanggal', 'tanggal_mulai');
            $table->date('tanggal_akhir')->nullable()->after('tanggal_mulai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sekolahs', function (Blueprint $table) {
            $table->dropColumn('jam_presensi');
            $table->time('jam_datang_mulai')->nullable();
            $table->time('jam_datang_akhir')->nullable();
            $table->time('jam_pulang_mulai')->nullable();
            $table->time('jam_pulang_akhir')->nullable();
        });

        Schema::table('liburs', function (Blueprint $table) {
            $table->renameColumn('tanggal_mulai', 'tanggal');
            $table->dropColumn('tanggal_akhir');
            $table->unique('tanggal');
        });
    }
};

