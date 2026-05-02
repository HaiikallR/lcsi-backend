<?php

use App\Http\Controllers\api\AdminController;
use App\Http\Controllers\api\DashboardController;
use App\Http\Controllers\api\PemberitahuanController;
use App\Http\Controllers\api\PemasukkanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\TicketingController;
use App\Http\Controllers\api\VerificationController;
use Mockery\VerificationDirector;
use App\Http\Controllers\api\PengeluaranController;
use App\Http\Controllers\api\TechniciansController;
use App\Http\Controllers\api\TagihanController;
use App\Models\Notifikasi;
use App\Http\Controllers\api\PertanyaanController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\PerangkatController;
use App\Http\Controllers\api\PelangganController;
use App\Http\Controllers\api\PermintaanUpgradeController;


Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/admin', [AdminController::class, 'index']);
Route::post('/admin', [AdminController::class, 'store']);
Route::put('/admin/{id}', [AdminController::class, 'update']);
Route::delete('/admin/{id}', [AdminController::class, 'destroy']);

Route::get('/verification', [VerificationController::class, 'index']);
Route::post('/verification/{id}/verify', [VerificationController::class, 'verify']);
Route::post('/verification/{id}/reject', [VerificationController::class, 'reject']);

Route::get('/ticket', [TicketingController::class, 'index']);
Route::post('/ticket-store', [TicketingController::class, 'store']);
Route::put('/{id}/complete', [TicketingController::class, 'markAsCompleted']);
Route::get('/{id}/whatsapp', [TicketingController::class, 'getWhatsAppDetail']);
Route::delete('/{id}', [TicketingController::class, 'destroy']);


Route::get('/pengeluaran', [PengeluaranController::class, 'index']);
Route::post('/pengeluaran-store', [PengeluaranController::class, 'store']);
Route::get('/pengeluaran-report', [PengeluaranController::class, 'report']);
Route::delete('/pengeluaran/{id}', [PengeluaranController::class, 'destroy']);


Route::get('/tagihan', [TagihanController::class, 'index']);
Route::post('/tagihan-massal', [TagihanController::class, 'generateMassal']);
Route::post('/tagihan-tunggal', [TagihanController::class, 'generateTunggal']);
Route::put('/tagihan/{id}/status', [TagihanController::class, 'updateStatus']);
Route::delete('/tagihan/{id}', [TagihanController::class, 'destroy']);


Route::get('/pemasukkan', [PemasukkanController::class, 'index']);
Route::post('/pemasukkan-store', [PemasukkanController::class, 'store']);
Route::delete('/pemasukkan/{id}', [PemasukkanController::class, 'destroy']);


Route::get('/notifikasi', [PemberitahuanController::class, 'index']);
Route::post('/broadcast', [PemberitahuanController::class, 'sendBroadcast']);
Route::delete('/{id}', [PemberitahuanController::class, 'destroy']);


Route::post('/login-admin', [AuthController::class, 'loginAdmin']);

// Route yang butuh login
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::prefix('pertanyaan')->group(function () {
    Route::get('/', [PertanyaanController::class, 'index']);
    Route::post('/', [PertanyaanController::class, 'store']);
    Route::put('/{id}', [PertanyaanController::class, 'update']);
    Route::delete('/{id}', [PertanyaanController::class, 'destroy']);
});


Route::prefix('perangkat')->group(function () {
    Route::get('/', [PerangkatController::class, 'index']);
    Route::post('/', [PerangkatController::class, 'store']);
    Route::put('/{id}', [PerangkatController::class, 'update']);
    Route::delete('/{id}', [PerangkatController::class, 'destroy']);
});



Route::prefix('pelanggan')->group(function () {
    Route::get('/', [PelangganController::class, 'index']);
    Route::post('/', [PelangganController::class, 'store']);
    Route::put('/{id}', [PelangganController::class, 'update']);
    Route::delete('/{id}', [PelangganController::class, 'destroy']);
});

Route::prefix('upgrade-requests')->group(function () {
    Route::get('/', [PermintaanUpgradeController::class, 'index']);
    Route::post('/{id}/approve', [PermintaanUpgradeController::class, 'approve']);
    Route::post('/{id}/reject', [PermintaanUpgradeController::class, 'reject']);
});
