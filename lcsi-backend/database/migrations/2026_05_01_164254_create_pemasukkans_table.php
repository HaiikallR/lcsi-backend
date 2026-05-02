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
        Schema::create('pemasukkans', function (Blueprint $table) {
            $table->id('id_pemasukan'); // Primary Key

            // String idUser (ID Pelanggan/User terkait)
            $table->unsignedBigInteger('id_pelanggan');

            // String namaPelanggan
            $table->string('nama_pelanggan');

            // String jenisPemasukan (Misal: Tagihan Bulanan, Pasang Baru)
            $table->string('jenis_pemasukan');

            // int jumlahBayar
            $table->integer('jumlah_bayar');

            // String metodeBayar (Misal: Transfer Bank, Tunai)
            $table->string('metode_bayar');

            // String buktiBayar (Berisi nama file atau URL foto bukti transfer)
            $table->string('bukti_bayar')->nullable();

            // String keterangan
            $table->text('keterangan')->nullable();

            // String status (Misal: Lunas, Pending)
            $table->string('status')->default('Pending');

            // String bulanTagihan dan tahunTagihan
            $table->string('bulan_tagihan');
            $table->string('tahun_tagihan');

            // Timestamp tanggalBayar
            $table->timestamp('tanggal_bayar')->nullable();

            $table->timestamps();
            // 2. Definisikan relasi Foreign Key secara formal
            $table->foreign('id_pelanggan')
                ->references('id_pelanggan') // Merujuk ke Primary Key tabel pelanggan
                ->on('pelanggans')
                ->onDelete('cascade'); // Jika pelanggan dihapus, data pemasukan terkait ikut terhapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemasukkans');
    }
};
