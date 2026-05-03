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
        Schema::create('permintaan_upgrades', function (Blueprint $table) {
            $table->id();
            $table->string('paket_lama');
            $table->string('paket_baru');
            $table->string('harga_baru');
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu'); // Status (Misal: menunggu, disetujui, ditolak)
            $table->timestamp('disetujui_pada')->nullable(); // Waktu Persetujuan atau Penolakan (Nullable karena diisi belakangan)
            $table->timestamp('ditolak_pada')->nullable();
            $table->timestamps();

            $table->foreignId('id_pelanggan')->constrained('pelanggans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_upgrades');
    }
};
