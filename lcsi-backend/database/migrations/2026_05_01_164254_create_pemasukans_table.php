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
        Schema::create('pemasukans', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_pemasukan'); // Misal: Tagihan Bulanan, Pasang Baru
            $table->integer('jumlah_bayar');
            $table->string('metode_bayar'); // Misal: Transfer Bank, Tunai
            $table->string('bukti_bayar')->nullable(); // Berisi nama file atau URL foto bukti transfer
            $table->text('keterangan')->nullable();
            $table->enum('status', ['lunas', 'menunggu'])->default('menunggu');
            $table->string('bulan_tagihan');
            $table->string('tahun_tagihan');
            $table->timestamp('tanggal_bayar')->nullable();
            $table->timestamps();

            // Relations
            $table->foreignId('id_pelanggan')->constrained('pelanggans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemasukans');
    }
};
