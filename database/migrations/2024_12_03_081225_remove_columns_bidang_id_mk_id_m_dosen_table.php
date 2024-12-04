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
            $table->dropForeign(['bidang_id']);
            $table->dropForeign(['mk_id']);
            
            $table->dropColumn(['bidang_id', 'mk_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_dosen', function (Blueprint $table) {
            // Recreate the columns
            $table->unsignedBigInteger('bidang_id');
            $table->unsignedBigInteger('foreign_key_column2');
            
            // Re-add the foreign key constraints
            $table->foreign('bidang_id')
                  ->references('bidang_id')
                  ->on('m_bidang')
                  ->onDelete('cascade');

            $table->foreign('mk_id')
                  ->references('mk_id')
                  ->on('m_matkul')
                  ->onDelete('cascade');
        });
    }
};
