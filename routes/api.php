<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\PemasukanController;
use App\Http\Controllers\Api\PengeluaranController;
use App\Http\Controllers\Api\PelangganController;
use App\Http\Controllers\Api\PertanyaanController;
use App\Http\Controllers\Api\NotifikasiController;
use App\Http\Controllers\Api\PermintaanUpgradeController;
use App\Http\Controllers\Api\PerangkatController;
use App\Http\Controllers\Api\TagihanController;
use App\Http\Controllers\Api\TeknisiController;
use App\Http\Controllers\Api\TiketController;
use App\Http\Controllers\Api\PelangganAuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\PelangganPembayaranController;
use App\Http\Controllers\Api\PelangganUpgradeController;

Route::post('/pelanggan/login', [PelangganAuthController::class, 'login'])->name('api.login');


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/pelanggan/logout', [PelangganAuthController::class, 'logout']);
    Route::get('/pelanggan/me', [PelangganAuthController::class, 'me']);
    Route::get('/pelanggan/dashboard', [PelangganController::class, 'dashboard']);
    Route::get('/pelanggan/tagihan', [PelangganPembayaranController::class, 'tagihanSaya']);
    Route::post('/pelanggan/bayar', [PelangganPembayaranController::class, 'bayar']);
    Route::get('/pelanggan/tagihan/{id}/pemasukan', [PelangganPembayaranController::class, 'cekPembayaran']);
    // ✅ Taruh di atas apiResource pelanggan
    Route::get('/pelanggan/upgrade', [PelangganUpgradeController::class, 'index']);
    Route::post('/pelanggan/upgrade', [PelangganUpgradeController::class, 'store']);
    Route::get('/pelanggan/notifikasi', [NotifikasiController::class, 'index']);
    Route::get('/pelanggan/pertanyaan', [PertanyaanController::class, 'index']);
    // ✅ Tambahkan route ini
    Route::put('/pelanggan/fcm-token', [PelangganAuthController::class, 'updateFcmToken']);
    Route::apiResource('admin', AdminController::class)->names('api.admin');
    Route::apiResource('pelanggan', PelangganController::class)->names('api.pelanggan');
    Route::put('pelanggan/{pelanggan}/fcm-token', [PelangganController::class, 'updateFcmToken'])->name('api.pelanggan.update-fcm-token');
    Route::apiResource('perangkat', PerangkatController::class)->names('api.perangkat');
    Route::apiResource('teknisi', TeknisiController::class)->names('api.teknisi');
    Route::post('tiket/{tiket}/kirim-whatsapp', [TiketController::class, 'kirimWhatsapp'])->name('api.tiket.kirim-whatsapp');
    Route::post('tiket/{tiket}/selesai', [TiketController::class, 'selesai'])->name('api.tiket.selesai');
    Route::apiResource('tiket', TiketController::class)->names('api.tiket');
    Route::apiResource('pengeluaran', PengeluaranController::class)->names('api.pengeluaran');
    Route::apiResource('pemasukan', PemasukanController::class)->names('api.pemasukan');
    Route::apiResource('pertanyaan', PertanyaanController::class)->names('api.pertanyaan');
    Route::apiResource('notifikasi', NotifikasiController::class)->names('api.notifikasi');
    Route::apiResource('permintaan-upgrade', PermintaanUpgradeController::class)->names('api.permintaan-upgrade');
    Route::apiResource('tagihan', TagihanController::class)->names('api.tagihan');
    // ── Pembayaran Pelanggan ──────────────────────────

});
