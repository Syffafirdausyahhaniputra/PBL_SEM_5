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
        Schema::create('t_data_sertifikasi', function (Blueprint $table) {
            $table->id('data_sertif_id');
            $table->unsignedBigInteger('sertif_id')->index(); // indexing untuk ForeignKey
            $table->unsignedBigInteger('dosen_id')->index(); // indexing untuk ForeignKey
            $table->string('status');
            $table->timestamps();

            $table->foreign('sertif_id')->references('sertif_id')->on('t_sertifikasi');
            $table->foreign('dosen_id')->references('dosen_id')->on('m_dosen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_data_sertifikasi');
    }
};
