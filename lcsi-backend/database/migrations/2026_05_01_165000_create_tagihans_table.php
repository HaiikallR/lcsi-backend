<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ForeignIdColumnDefinition;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id('id_tagihan'); // Primary Key

            // Relasi & Identitas
            $table->unsignedBigInteger('id_pelanggan');


            // Keuangan & Waktu
            $table->integer('jumlah');
            $table->string('bulan');
            $table->string('tahun');

            // Bukti & Catatan
            $table->string('bukti_bayar')->nullable(); // Pengganti buktiUrl (menyimpan nama file)
            $table->text('catatan_admin')->nullable();
            $table->string('status')->default('Belum Bayar');

            // Timestamp (Sesuai variabel kamu)
            $table->timestamp('tanggal_bayar')->nullable();
            $table->timestamp('tanggal_verifikasi')->nullable(); // Pengganti tglVerifikasi & waktuVerifikasi

            // createdAt & updatedAt otomatis dari Laravel
            $table->timestamps();

            $table->foreign('id_pelanggan')
                ->references('id_pelanggan')
                ->on('pelanggans')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};
