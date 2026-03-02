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
            $table->time('jam_datang_mulai')->nullable()->after('email');
            $table->time('jam_datang_akhir')->nullable()->after('jam_datang_mulai');
            $table->time('jam_pulang_mulai')->nullable()->after('jam_datang_akhir');
            $table->time('jam_pulang_akhir')->nullable()->after('jam_pulang_mulai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sekolahs', function (Blueprint $table) {
            $table->dropColumn(['jam_datang_mulai', 'jam_datang_akhir', 'jam_pulang_mulai', 'jam_pulang_akhir']);
        });
    }
};

