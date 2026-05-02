<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique(); // Email (Dibuat unik agar tidak ada email ganda)
            $table->string('password'); // Password (Wajib ada untuk login, meskipun tidak ada di list data kamu)
            $table->string('status')->default('aktif'); // Status (Misal: Active, Inactive)

            // Kolom untuk menyimpan token login (Penting untuk API Sanctum)


            // Timestamp createdAt & updatedAt (Otomatis dibuat oleh Laravel)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
