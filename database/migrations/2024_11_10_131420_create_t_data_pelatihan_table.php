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
        Schema::create('t_data_pelatihan', function (Blueprint $table) {
            $table->id('data_pelatihan_id');
            $table->unsignedBigInteger('pelatihan_id')->index(); // indexing untuk ForeignKey
            $table->unsignedBigInteger('dosen_id')->index(); // indexing untuk ForeignKey
            $table->string('status');
            $table->timestamps();

            $table->foreign('pelatihan_id')->references('pelatihan_id')->on('t_pelatihan');
            $table->foreign('dosen_id')->references('dosen_id')->on('m_dosen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_data_pelatihan');
    }
};
