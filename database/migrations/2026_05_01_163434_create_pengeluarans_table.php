<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->integer('jumlah'); //Untuk keuangan, sebenarnya disarankan decimal, tapi jika ingin int sesuaikan variabel
            $table->string('kategori');
            $table->string('bulan');
            $table->string('tahun');
            $table->string('teknisi');
            $table->timestamps();

            $table->foreignId('id_tiket')->constrained('tikets')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluarans');
    }
};
