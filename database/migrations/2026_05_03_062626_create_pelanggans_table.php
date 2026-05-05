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
            $table->timestamp('email_verifikasi')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('no_hp');
            $table->longText('alamat');
            $table->string('paket_langganan');
            $table->enum('status', ['aktif', 'tidak aktif'])->default('aktif');
            $table->string('device_token')->nullable(); // Untuk 1 device per pelanggan, atau bisa buat tabel terpisah untuk multi-device

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
