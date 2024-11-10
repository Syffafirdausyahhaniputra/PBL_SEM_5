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
        Schema::create('t_pelatihan', function (Blueprint $table) {
            $table->id('pelatihan_id');
            $table->unsignedBigInteger('level_id')->index(); // indexing untuk ForeignKey
            $table->unsignedBigInteger('bidang_id')->index(); // indexing untuk ForeignKey
            $table->unsignedBigInteger('mk_id')->index(); // indexing untuk ForeignKey
            $table->unsignedBigInteger('vendor_id')->index(); // indexing untuk ForeignKey
            $table->string('nama_pelatihan');
            $table->date('tanggal');
            $table->string('kuota');
            $table->string('lokasi');
            $table->string('periode');
            $table->timestamps();

            $table->foreign('level_id')->references('level_id')->on('m_level_pelatihan');
            $table->foreign('bidang_id')->references('bidang_id')->on('m_bidang');
            $table->foreign('mk_id')->references('mk_id')->on('m_matkul');
            $table->foreign('vendor_id')->references('vendor_id')->on('m_vendor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_pelatihan');
    }
};
