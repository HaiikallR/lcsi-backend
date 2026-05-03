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
        Schema::create('pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->integer('jumlah'); //Untuk keuangan, sebenarnya disarankan decimal, tapi jika ingin int sesuaikan variabel
            $table->string('kategori');
            $table->string('bulan');
            $table->string('tahun');
            $table->timestamps();

            $table->foreignId('id_tiket')->unique()->constrained('tikets')->onDelete('cascade');
            $table->foreignId('id_teknisi')->constrained('teknisis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluarans');
    }
};
