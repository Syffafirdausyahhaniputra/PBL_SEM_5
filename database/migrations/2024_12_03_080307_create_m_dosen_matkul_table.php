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
        Schema::create('m_dosen_matkul', function (Blueprint $table) {
            $table->id('dosen_matkul_id');
            $table->unsignedBigInteger('dosen_id')->index(); // indexing untuk ForeignKey
            $table->unsignedBigInteger('mk_id')->index(); // indexing untuk ForeignKey
            $table->timestamps();

            $table->foreign('dosen_id')->references('dosen_id')->on('m_dosen');
            $table->foreign('mk_id')->references('mk_id')->on('m_matkul');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_dosen_matkul');
    }
};
