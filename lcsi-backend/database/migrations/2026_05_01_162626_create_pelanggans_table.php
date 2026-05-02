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
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->id('id_pelanggan'); // Primary Key

            // Identitas & Akun
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('kata_sandi'); // Wajib ada untuk login backend
            $table->string('no_telp');
            $table->text('alamat');

            // Layanan & Status
            $table->string('paket_langganan'); // Gabungan dari paket & paketLangganan
            $table->string('status')->default('aktif');

            // Keuangan
            $table->integer('total_tagihan')->default(0); // Pengganti totalTagihan/userTotalTagihan

            // Keamanan & Notifikasi
            $table->text('fcm_token')->nullable(); // Token untuk push notification Flutter

            // Waktu
            $table->timestamp('dibuat_pada')->useCurrent(); // Pengganti createdAt/userCreatedAt
            $table->timestamps(); // Standar Laravel (created_at & updated_at)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggans');
    }
};
