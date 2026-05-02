<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perangkats', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Nama perangkat
            $table->string('merk');
            $table->string('serial_number')->unique(); // String serialNumber (Dibuat unik karena setiap perangkat punya SN berbeda)
            $table->enum('status', ['tersedia', 'digunakan', 'perbaikan'])->default('tersedia'); // String status (Misal: tersedia, digunakan, perbaikan)
            $table->timestamps();

            $table->foreignId('id_admin')->unique()->constrained('admins')->onDelete('cascade'); // Foreign key ke tabel admins (Setiap perangkat hanya bisa dimiliki oleh satu admin)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perangkats');
    }
};
