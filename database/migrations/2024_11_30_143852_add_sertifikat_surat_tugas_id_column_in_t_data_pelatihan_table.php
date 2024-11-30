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
        Schema::table('t_data_pelatihan', function (Blueprint $table) {
            $table->string('sertifikat')->nullable()->after('status');
            $table->unsignedBigInteger('surat_tugas_id')->nullable()->after('dosen_id');

            $table->foreign('surat_tugas_id')->references('surat_tugas_id')->on('m_surat_tugas')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_data_pelatihan', function (Blueprint $table) {
            $table->dropColumn(['sertifikat', 'surat_tugas_id']); // Hapus kolom jika rollback
        });
    }
};
