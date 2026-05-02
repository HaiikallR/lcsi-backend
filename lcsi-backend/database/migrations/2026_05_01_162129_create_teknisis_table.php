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
        Schema::create('teknisis', function (Blueprint $table) {
            // idTeknisi (Primary Key)
            $table->id('id_teknisi');

            // String nama
            $table->string('nama');

            // String noHp
            $table->string('no_hp');

            // String wilayah (Misal: Bekasi, Jakarta, dsb)
            $table->string('wilayah');

            // String status (Misal: Aktif, Standby, Off)
            $table->string('status')->default('Aktif');

            // createdAt & updatedAt otomatis dari Laravel
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teknisis');
    }
};
