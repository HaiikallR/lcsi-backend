<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengeluarans', function (Blueprint $table) {
            // int idExpenses (Primary Key)
            $table->id('id_pengeluaran'); // Jika ingin nama kolom id sesuai variabel, bisa ditentukan di sini

            // String idTiket (Foreign Key ke tabel tickets)
            // Menggunakan string sesuai variabelmu, pastikan tipe datanya sama dengan tabel tickets
            $table->unsignedBigInteger('id_tiket');

            // String judul
            $table->string('judul');

            // int jumlah
            // Untuk keuangan, sebenarnya disarankan decimal, tapi jika ingin int sesuai variabel:
            $table->integer('jumlah');

            // String kategori
            $table->string('kategori');

            // String bulan dan String tahun
            $table->string('bulan');
            $table->string('tahun');

            // Timestamp tanggal
            $table->timestamp('tanggal')->useCurrent();

            // String teknisi
            $table->string('teknisi');

            $table->timestamps(); // createAt dan updatedAt otomatis

            // 2. Definisikan Foreign Key Constraint secara formal
            $table->foreign('id_tiket')
                ->references('id_tiket') // Nama kolom di tabel sumber
                ->on('tikets')           // Nama tabel sumber
                ->onDelete('cascade');  // Jika tiket dihapus, data pengeluaran terkait ikut terhapus
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluarans');
    }
};
