<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perangkats', function (Blueprint $table) {
            $table->id('id_perangkat'); // Primary Key

            // String namaPerangkat
            $table->string('nama_perangkat');

            // String merk
            $table->string('merk');

            // String serialNumber (Dibuat unik karena setiap perangkat punya SN berbeda)
            $table->string('serial_number')->unique();

            // String assignedTo 
            // Jika ini berisi nama orang (String), gunakan string. 
            // Jika ini relasi ke tabel Admin, gunakan foreignId.
            $table->string('terpasang di')->nullable();

            // String status (Misal: Ready, In Use, Repair)
            $table->string('status')->default('Teredia');

            // Timestamp updatedAt & createdAt
            // Laravel otomatis menghandle ini lewat timestamps()
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perangkats');
    }
};
