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
        Schema::create('tikets', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_pekerjaan');
            $table->string('calon_pelanggan_nama')->nullable();
            $table->string('calon_pelanggan_no_hp')->nullable();
            $table->longText('calon_pelanggan_alamat')->nullable();
            $table->integer('ongkos_teknisi')->default(0);
            $table->enum('status', ['in progress', 'selesai'])->default('in progress');
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamps();

            $table->foreignId('id_pelanggan')->nullable()->constrained('pelanggans')->onDelete('cascade');
            $table->foreignId('id_teknisi')->constrained('teknisis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tikets');
    }
};
