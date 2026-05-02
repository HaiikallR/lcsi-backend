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
            $table->id();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('password'); // Wajib ada untuk login backend
            $table->string('no_hp');
            $table->longText('alamat');
            $table->string('paket_langganan'); // Gabungan dari paket & paketLangganan
            $table->enum('status', ['aktif', 'tidak aktif'])->default('aktif');

            // Keamanan & Notifikasi
            $table->text('fcm_token')->nullable(); // Token untuk push notification Flutter
            $table->timestamps();
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
