<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemberitahuans', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('isi_pesan'); // Menggunakan text agar pesan tidak terpotong jika panjang
            $table->string('kategori'); // String kategori
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemberitahuans');
    }
};
