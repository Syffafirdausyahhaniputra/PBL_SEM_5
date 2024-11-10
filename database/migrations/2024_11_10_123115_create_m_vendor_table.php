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
        Schema::create('m_vendor', function (Blueprint $table) {
            $table->id('vendor_id');
            $table->string('vendor_nama', 100)->unique();
            $table->string('vendor_alamat');
            $table->string('vendor_kota', 100);
            $table->string('vendor_no_telf');
            $table->string('vendor_alamat_web');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_vendor');
    }
};
