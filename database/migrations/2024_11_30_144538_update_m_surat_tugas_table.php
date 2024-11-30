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
        Schema::table('m_surat_tugas', function (Blueprint $table) {
            $table->dropForeign('m_surat_tugas_dosen_id_foreign');
            $table->dropColumn('dosen_id');

            $table->string('nomor_surat')->nullable()->after('surat_tugas_id');
            $table->string('nama_surat')->nullable()->after('nomor_surat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_surat_tugas', function (Blueprint $table) {
            // Remove nama_surat column
            $table->dropColumn(['nomor_surat', 'nama_surat']);
        });
    }
};
