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
            $table->id();
            $table->unsignedInteger('id_pelanggan'); // Menggunakan string untuk menampung 'dynamic'
            $table->string('nama_pelanggan');
            $table->string('alamat_pelanggan');
            $table->string('nomor_hp_pelanggan'); // Menggunakan string sesuai diskusi no hp sebelumnya

            $table->unsignedBigInteger('id_teknisi')->nullable();
            $table->string('jenis_pekerjaan'); // Contoh: Pasang Baru, Perbaikan Jaringan
            $table->integer('ongkos_teknisi')->default(0);
            $table->enum('status', ['menunggu', 'dalam proses', 'selesai'])->default('menunggu');
            $table->timestamps(); // createdAt & updatedAt otomatis
            $table->timestamp('tanggal_selesai')->nullable(); // Menyimpan tanggal selesai jika status berubah menjadi 'selesai'

            // Relasi dengan tabel teknisi
            $table->foreign('id_pelanggan')->references('id')->on('pelanggans')->onDelete('cascade');
            $table->foreign('id_teknisi')->references('id')->on('teknisis')->onDelete('set null');
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
