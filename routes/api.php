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
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::apiResource('admin', AdminController::class);
    Route::apiResource('pelanggan', PelangganController::class);
    Route::put('pelanggan/{pelanggan}/fcm-token', [PelangganController::class, 'updateFcmToken']);
    Route::apiResource('perangkat', PerangkatController::class);
    Route::apiResource('teknisi', TeknisiController::class);
    Route::post('tiket/{tiket}/kirim-whatsapp', [TiketController::class, 'kirimWhatsapp']);
    Route::post('tiket/{tiket}/selesai', [TiketController::class, 'selesai']);
    Route::apiResource('tiket', TiketController::class);
    Route::apiResource('pengeluaran', PengeluaranController::class);
    Route::apiResource('pemasukan', PemasukanController::class);
    Route::apiResource('pertanyaan', PertanyaanController::class);
    Route::apiResource('notifikasi', NotifikasiController::class);
    Route::apiResource('permintaan-upgrade', PermintaanUpgradeController::class);
    Route::apiResource('tagihan', TagihanController::class);
});
