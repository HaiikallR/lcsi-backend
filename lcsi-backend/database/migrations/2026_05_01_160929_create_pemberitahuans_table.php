<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemberitahuans', function (Blueprint $table) {
            $table->id('id_pemberitahuan'); // Primary Key

            // String judul
            $table->string('judul');

            // String isiPesan
            // Menggunakan text agar pesan tidak terpotong jika panjang
            $table->text('isi_pesan');

            // String kategori
            $table->string('kategori');

            // Timestamp tanggalSent
            // Kita gunakan timestamp agar sesuai dengan variabel kamu
            $table->timestamp('waktu')->useCurrent();


            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemberitahuans');
    }
};
