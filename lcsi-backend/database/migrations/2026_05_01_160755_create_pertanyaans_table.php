<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pertanyaans', function (Blueprint $table) {
            $table->id('id_pertanyaan'); // Primary Key default Laravel

            // String pertanyaan
            $table->string('pertanyaan');

            // String jawaban
            // Menggunakan text karena jawaban biasanya lebih panjang dari 255 karakter
            $table->text('jawaban');

            // String kategori (Misal: Jaringan, Perangkat, Akun)
            $table->string('kategori');

            // int urutan
            // Digunakan untuk menyusun tampilan di Flutter (OrderBy urutan)
            $table->integer('urutan')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pertanyaans');
    }
};
