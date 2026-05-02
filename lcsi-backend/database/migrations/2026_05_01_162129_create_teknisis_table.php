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
        Schema::create('teknisis', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_hp');
            $table->string('wilayah'); // String wilayah (Misal: Bekasi, Jakarta, dsb)
            $table->enum('status', ['aktif', 'siap', 'tidak aktif'])->default('Aktif'); // String status (Misal: Aktif, Siap, Tidak Aktif)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teknisis');
    }
};
