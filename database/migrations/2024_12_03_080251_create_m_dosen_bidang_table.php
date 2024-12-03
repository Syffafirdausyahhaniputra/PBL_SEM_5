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
        Schema::create('m_dosen_bidang', function (Blueprint $table) {
            $table->id('dosen_bidang_id');
            $table->unsignedBigInteger('dosen_id')->index(); // indexing untuk ForeignKey
            $table->unsignedBigInteger('bidang_id')->index(); // indexing untuk ForeignKey
            $table->timestamps();

            $table->foreign('dosen_id')->references('dosen_id')->on('m_dosen');
            $table->foreign('bidang_id')->references('bidang_id')->on('m_bidang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_dosen_bidang');
    }
};
