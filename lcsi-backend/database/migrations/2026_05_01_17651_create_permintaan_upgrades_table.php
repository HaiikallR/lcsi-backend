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
            $table->unsignedBigInteger('id_pelanggan');
            $table->string('paket_lama');
            $table->string('paket_baru');
            $table->string('harga_baru');
            $table->string('status')->default('menunggu persetujuan'); // Status (Misal: Menunggu, Disetujui, Ditolak)
            $table->timestamp('waktu_pengajuan')->useCurrent(); // Waktu Pengajuan (Sesuai variabel timestamp)
            $table->timestamp('disetujui_pada')->nullable(); // Waktu Persetujuan atau Penolakan (Nullable karena diisi belakangan)
            $table->timestamp('ditolak_pada')->nullable();
            $table->timestamps();

            // Definisi Foreign Key Constraint
            $table->foreign('id_pelanggan')
                ->references('id_pelanggan') // Primary Key di tabel pelanggan
                ->on('pelanggans')           // Nama tabel referensi
                ->onDelete('cascade');      // Jika pelanggan dihapus, riwayat upgrade ikut terhapus
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
