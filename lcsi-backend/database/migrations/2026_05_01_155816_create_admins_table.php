<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            // id_admin (Primary Key)
            $table->id('id_admin');

            // Nama Admin
            $table->string('nama');

            // Email (Dibuat unik agar tidak ada email ganda)
            $table->string('email')->unique();

            // Password (Wajib ada untuk login, meskipun tidak ada di list data kamu)
            $table->string('password');

            // Status (Misal: Active, Inactive)
            $table->string('status')->default('aktif');

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
