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
        Schema::create('m_kompetensi_prodi', function (Blueprint $table) {
            $table->id('kompetensi_prodi_id');
            $table->unsignedBigInteger('prodi_id')->index(); // indexing untuk ForeignKey
            $table->unsignedBigInteger('bidang_id')->index(); // indexing untuk ForeignKey

            $table->foreign('prodi_id')->references('prodi_id')->on('m_prodi');
            $table->foreign('bidang_id')->references('bidang_id')->on('m_bidang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_kompetensi_prodi');
    }
};
