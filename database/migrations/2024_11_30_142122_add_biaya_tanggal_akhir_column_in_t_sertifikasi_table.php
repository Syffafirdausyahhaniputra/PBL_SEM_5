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
        Schema::table('t_sertifikasi', function (Blueprint $table) {
            $table->string('biaya')->nullable()->after('nama_sertif');
            $table->date('tanggal_akhir')->nullable()->after('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_sertifikasi', function (Blueprint $table) {
            $table->dropColumn(['biaya', 'tanggal_akhir']); // Hapus kolom jika rollback
        });
    }
};
