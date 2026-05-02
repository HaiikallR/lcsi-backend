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
            $table->id('id_permintaan_upgrade'); // Primary Key

            // String idUser dan namaUser
            $table->unsignedBigInteger('id_pelanggan');

            // Detail Paket
            $table->string('paket_lama');
            $table->string('paket_baru');
            $table->string('harga_baru');

            // Status (Misal: Pending, Approved, Rejected)
            $table->string('status')->default('Menunggu_persetujuan');

            // Waktu Pengajuan (Sesuai variabel timestamp)
            $table->timestamp('waktu_pengajuan')->useCurrent();

            // Waktu Persetujuan atau Penolakan (Nullable karena diisi belakangan)
            $table->timestamp('disetujui_pada')->nullable();
            $table->timestamp('ditolak_pada')->nullable();

            $table->timestamps(); // createAt dan updatedAt standar Laravel

            // 2. Definisi Foreign Key Constraint
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
