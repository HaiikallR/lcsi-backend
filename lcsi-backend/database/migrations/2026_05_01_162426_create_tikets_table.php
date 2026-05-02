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
        Schema::create('tikets', function (Blueprint $table) {
            $table->id('id_tiket'); // Primary Key

            // Relasi & Identitas
            $table->string('id_pelanggan'); // Menggunakan string untuk menampung 'dynamic'
            $table->string('nama_pelanggan');
            $table->string('alamat_pelanggan');
            $table->string('phone_pelanggan'); // Menggunakan string sesuai diskusi no hp sebelumnya

            // Penugasan Teknisi
            $table->string('id_teknisi')->nullable();
            $table->string('nama_teknisi')->nullable();

            // Detail Pekerjaan
            $table->string('jenis_pekerjaan'); // Contoh: Pasang Baru, Perbaikan Jaringan
            $table->integer('ongkos_teknisi')->default(0);
            $table->enum('status', ['Pending', 'Dalam Proses', 'Selesai'])->default('Pending');

            // Waktu (Sesuai variabel kamu)
            $table->timestamp('tanggal')->useCurrent(); // Waktu tiket dibuat
            $table->timestamp('selesai_pada')->nullable(); // Pengganti completedAt

            $table->timestamps(); // createdAt & updatedAt otomatis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tikets');
    }
};
