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
        Schema::create('m_surat_tugas', function (Blueprint $table) {
            $table->id('surat_tugas_id');
            $table->unsignedBigInteger('dosen_id')->index(); // indexing untuk ForeignKey
            $table->string('status');
            $table->timestamps();

            $table->foreign('dosen_id')->references('dosen_id')->on('m_dosen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_surat_tugas');
    }
};
