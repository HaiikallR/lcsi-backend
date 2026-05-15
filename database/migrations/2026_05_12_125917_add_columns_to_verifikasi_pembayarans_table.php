<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('verifikasi_pembayarans', function (Blueprint $table) {
            $table->foreignId('id_pelanggan')
                ->constrained('pelanggans')
                ->onDelete('cascade');
            $table->foreignId('id_tagihan')
                ->constrained('tagihans')
                ->onDelete('cascade');
            $table->string('bukti_transfer');        // path file foto bukti
            $table->string('bulan');
            $table->string('tahun');
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])
                ->default('menunggu');
            $table->text('catatan')->nullable();     // catatan dari admin
            $table->timestamp('diverifikasi_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('verifikasi_pembayarans', function (Blueprint $table) {
            $table->dropForeign(['id_pelanggan']);
            $table->dropForeign(['id_tagihan']);
            $table->dropColumn([
                'id_pelanggan',
                'id_tagihan',
                'bukti_transfer',
                'bulan',
                'tahun',
                'status',
                'catatan',
                'diverifikasi_pada'
            ]);
        });
    }
};
