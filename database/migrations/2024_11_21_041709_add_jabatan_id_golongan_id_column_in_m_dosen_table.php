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
        Schema::table('m_dosen', function (Blueprint $table) {
            $table->unsignedBigInteger('golongan_id')->nullable()->after('user_id');
            $table->unsignedBigInteger('jabatan_id')->nullable()->after('golongan_id');

            $table->foreign('golongan_id')->references('golongan_id')->on('m_golongan')->onDelete('cascade');;
            $table->foreign('jabatan_id')->references('jabatan_id')->on('m_jabatan')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_dosen', function (Blueprint $table) {
            $table->dropColumn(['golongan_id', 'jabatan_id']); // Hapus kolom jika rollback
        });
    }
};
