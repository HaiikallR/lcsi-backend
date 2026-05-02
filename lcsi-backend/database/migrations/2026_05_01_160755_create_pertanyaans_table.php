<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pertanyaans', function (Blueprint $table) {
            $table->id();
            $table->string('pertanyaan');
            $table->longText('jawaban'); // Menggunakan text karena jawaban biasanya lebih panjang dari 255 karakter
            $table->string('kategori'); // String kategori (Misal: Jaringan, Perangkat, Akun)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pertanyaans');
    }
};
