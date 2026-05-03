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
        Schema::create('perangkats', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perangkat');
            $table->string('merk');
            $table->string('serial_number')->unique();
            $table->enum('status', ['tersedia', 'digunakan', 'perbaikan'])->default('tersedia');
            $table->foreignId('id_pelanggan')->unique()->constrained('pelanggans')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perangkats');
    }
};
