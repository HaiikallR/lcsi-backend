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
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id();
            $table->integer('jumlah');
            $table->string('bulan');
            $table->string('tahun');
            $table->text('catatan')->nullable();
            $table->enum('status', ['belum bayar', 'menunggu', 'sudah bayar'])->default('belum bayar');
            $table->timestamp('tanggal_bayar')->nullable();
            $table->timestamp('tanggal_verifikasi')->nullable();
            $table->timestamps();

            $table->foreignId('id_pelanggan')->constrained('pelanggans')->onDelete('cascade');
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
