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
            $table->unsignedBigInteger('pangkat_id')->nullable()->after('user_id');

            $table->foreign('pangkat_id')->references('pangkat_id')->on('m_pangkat')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_dosen', function (Blueprint $table) {
            $table->dropColumn(['pangkat_id']); // Hapus kolom jika rollback
        });
    }
};
