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
        Schema::create('m_dosen', function (Blueprint $table) {
            $table->id('dosen_id');
            $table->unsignedBigInteger('user_id')->index(); // indexing untuk ForeignKey
            $table->unsignedBigInteger('mk_id')->index(); // indexing untuk ForeignKey
            $table->unsignedBigInteger('bidang_id')->index(); // indexing untuk ForeignKey
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('m_user');
            $table->foreign('mk_id')->references('mk_id')->on('m_matkul');
            $table->foreign('bidang_id')->references('bidang_id')->on('m_bidang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_dosen');
    }
};
