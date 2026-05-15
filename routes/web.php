<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\PelangganController;
use App\Http\Controllers\Web\PermintaanUpgradeController;
use App\Http\Controllers\Web\TeknisiController;
use App\Http\Controllers\Web\PerangkatController;
use App\Http\Controllers\Web\TiketController;
use App\Http\Controllers\Web\TagihanController;
use App\Http\Controllers\Web\PemasukanController;
use App\Http\Controllers\Web\PengeluaranController;
use App\Http\Controllers\Web\NotifikasiController;
use App\Http\Controllers\Web\PertanyaanController;
use App\Http\Controllers\Web\VerifikasiPembayaranController;
use Illuminate\Support\Facades\Route;

// ── Redirect root ────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));

// ── Auth Publik ──────────────────────────────────────
Route::middleware('guest:admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});


// ── Protected ────────────────────────────────────────
Route::middleware('auth:admin')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Admin ────────────────────────────────────────
    Route::get('/admin',             [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/create',      [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admin',            [AdminController::class, 'store'])->name('admin.store');
    Route::get('/admin/{admin}/edit', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/admin/{admin}',     [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/admin/{admin}',  [AdminController::class, 'destroy'])->name('admin.destroy');

    // ── Pelanggan ────────────────────────────────────
    Route::get('/pelanggan',                  [PelangganController::class, 'index'])->name('pelanggan.index');
    Route::get('/pelanggan/create',           [PelangganController::class, 'create'])->name('pelanggan.create');
    Route::post('/pelanggan',                 [PelangganController::class, 'store'])->name('pelanggan.store');
    Route::get('/pelanggan/{pelanggan}',      [PelangganController::class, 'show'])->name('pelanggan.show');
    Route::get('/pelanggan/{pelanggan}/edit', [PelangganController::class, 'edit'])->name('pelanggan.edit');
    Route::put('/pelanggan/{pelanggan}',      [PelangganController::class, 'update'])->name('pelanggan.update');
    Route::delete('/pelanggan/{pelanggan}',   [PelangganController::class, 'destroy'])->name('pelanggan.destroy');

    // ── Teknisi ──────────────────────────────────────
    Route::get('/teknisi',                [TeknisiController::class, 'index'])->name('teknisi.index');
    Route::get('/teknisi/create',         [TeknisiController::class, 'create'])->name('teknisi.create');
    Route::post('/teknisi',               [TeknisiController::class, 'store'])->name('teknisi.store');
    Route::get('/teknisi/{teknisi}/edit', [TeknisiController::class, 'edit'])->name('teknisi.edit');
    Route::put('/teknisi/{teknisi}',      [TeknisiController::class, 'update'])->name('teknisi.update');
    Route::delete('/teknisi/{teknisi}',   [TeknisiController::class, 'destroy'])->name('teknisi.destroy');

    // ── Perangkat ────────────────────────────────────
    Route::get('/perangkat',                  [PerangkatController::class, 'index'])->name('perangkat.index');
    Route::get('/perangkat/create',           [PerangkatController::class, 'create'])->name('perangkat.create');
    Route::post('/perangkat',                 [PerangkatController::class, 'store'])->name('perangkat.store');
    Route::get('/perangkat/{perangkat}/edit', [PerangkatController::class, 'edit'])->name('perangkat.edit');
    Route::put('/perangkat/{perangkat}',      [PerangkatController::class, 'update'])->name('perangkat.update');
    Route::delete('/perangkat/{perangkat}',   [PerangkatController::class, 'destroy'])->name('perangkat.destroy');

    // ── Tiket ────────────────────────────────────────
    Route::get('/tiket',               [TiketController::class, 'index'])->name('tiket.index');
    Route::get('/tiket/create',        [TiketController::class, 'create'])->name('tiket.create');
    Route::post('/tiket',              [TiketController::class, 'store'])->name('tiket.store');
    Route::get('/tiket/{tiket}/whatsapp', [TiketController::class, 'whatsapp'])->name('tiket.whatsapp');
    Route::get('/tiket/{tiket}',       [TiketController::class, 'show'])->name('tiket.show');
    Route::get('/tiket/{tiket}/edit',  [TiketController::class, 'edit'])->name('tiket.edit');
    Route::put('/tiket/{tiket}',       [TiketController::class, 'update'])->name('tiket.update');
    Route::delete('/tiket/{tiket}',    [TiketController::class, 'destroy'])->name('tiket.destroy');

    // ── Tagihan ──────────────────────────────────────
    // ⚠️ Route khusus (massal) HARUS di atas route {tagihan}
    Route::get('/tagihan/massal',      [TagihanController::class, 'formMassal'])->name('tagihan.massal');
    Route::post('/tagihan/massal',     [TagihanController::class, 'storeMassal'])->name('tagihan.massal.store');

    Route::get('/tagihan',             [TagihanController::class, 'index'])->name('tagihan.index');
    Route::get('/tagihan/create',      [TagihanController::class, 'create'])->name('tagihan.create');
    Route::post('/tagihan',            [TagihanController::class, 'store'])->name('tagihan.store');
    Route::get('/tagihan/{tagihan}/edit',  [TagihanController::class, 'edit'])->name('tagihan.edit');
    Route::put('/tagihan/{tagihan}',       [TagihanController::class, 'update'])->name('tagihan.update');
    Route::delete('/tagihan/{tagihan}',    [TagihanController::class, 'destroy'])->name('tagihan.destroy');

    // ── Pemasukan ────────────────────────────────────
    Route::get(
        '/pemasukan/tagihan-pelanggan',
        [PemasukanController::class, 'getTagihanPelanggan']
    )->name('pemasukan.tagihan-pelanggan');

    Route::get('/pemasukan',                  [PemasukanController::class, 'index'])->name('pemasukan.index');
    // ✅ Route AJAX — harus di atas route lain
    Route::get('/pemasukan/create',           [PemasukanController::class, 'create'])->name('pemasukan.create');
    Route::post('/pemasukan',                 [PemasukanController::class, 'store'])->name('pemasukan.store');
    Route::get('/pemasukan/{pemasukan}/edit', [PemasukanController::class, 'edit'])->name('pemasukan.edit');
    Route::put('/pemasukan/{pemasukan}',      [PemasukanController::class, 'update'])->name('pemasukan.update');
    Route::delete('/pemasukan/{pemasukan}',   [PemasukanController::class, 'destroy'])->name('pemasukan.destroy');

    // ── Pengeluaran ──────────────────────────────────

    Route::get(
        '/pengeluaran/tiket-pelanggan',
        [PengeluaranController::class, 'getTiketPelanggan']
    )->name('pengeluaran.tiket-pelanggan');

    Route::get('/pengeluaran',                    [PengeluaranController::class, 'index'])->name('pengeluaran.index');
    Route::get('/pengeluaran/tiket-terbuka', [PengeluaranController::class, 'getTiketTerbuka'])->name('pengeluaran.tiket-terbuka');
    Route::get('/pengeluaran/create',             [PengeluaranController::class, 'create'])->name('pengeluaran.create');
    Route::post('/pengeluaran',                   [PengeluaranController::class, 'store'])->name('pengeluaran.store');
    Route::get('/pengeluaran/{pengeluaran}/edit',  [PengeluaranController::class, 'edit'])->name('pengeluaran.edit');
    Route::put('/pengeluaran/{pengeluaran}',       [PengeluaranController::class, 'update'])->name('pengeluaran.update');
    Route::delete('/pengeluaran/{pengeluaran}',    [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');

    // ── Permintaan Upgrade ───────────────────────────
    // Permintaan Upgrade
    Route::get(
        '/permintaan-upgrade',
        [PermintaanUpgradeController::class, 'index']
    )->name('permintaan-upgrade.index');
    Route::get(
        '/permintaan-upgrade/create',
        [PermintaanUpgradeController::class, 'create']
    )->name('permintaan-upgrade.create');
    Route::post(
        '/permintaan-upgrade',
        [PermintaanUpgradeController::class, 'store']
    )->name('permintaan-upgrade.store');
    Route::get(
        '/permintaan-upgrade/{permintaanUpgrade}',
        [PermintaanUpgradeController::class, 'show']
    )->name('permintaan-upgrade.show');
    Route::get(
        '/permintaan-upgrade/{permintaanUpgrade}/edit',
        [PermintaanUpgradeController::class, 'edit']
    )->name('permintaan-upgrade.edit');
    Route::put(
        '/permintaan-upgrade/{permintaanUpgrade}',
        [PermintaanUpgradeController::class, 'update']
    )->name('permintaan-upgrade.update');
    Route::delete(
        '/permintaan-upgrade/{permintaanUpgrade}',
        [PermintaanUpgradeController::class, 'destroy']
    )->name('permintaan-upgrade.destroy');

    // ✅ Route Approve & Tolak
    Route::post(
        '/permintaan-upgrade/{permintaanUpgrade}/setujui',
        [PermintaanUpgradeController::class, 'setujui']
    )->name('permintaan-upgrade.setujui');
    Route::post(
        '/permintaan-upgrade/{permintaanUpgrade}/tolak',
        [PermintaanUpgradeController::class, 'tolak']
    )->name('permintaan-upgrade.tolak');
    // ── Notifikasi ───────────────────────────────────

    // Notifikasi
    // ⚠️ Route khusus harus di atas route {notifikasi}
    Route::get(
        '/notifikasi',
        [NotifikasiController::class, 'index']
    )->name('notifikasi.index');
    Route::get(
        '/notifikasi/create',
        [NotifikasiController::class, 'create']
    )->name('notifikasi.create');
    Route::post(
        '/notifikasi',
        [NotifikasiController::class, 'store']
    )->name('notifikasi.store');
    Route::post(
        '/notifikasi/massal',
        [NotifikasiController::class, 'storeMassal']
    )->name('notifikasi.massal');
    Route::delete(
        '/notifikasi/{notifikasi}',
        [NotifikasiController::class, 'destroy']
    )->name('notifikasi.destroy');

    // ── Pertanyaan ───────────────────────────────────
    Route::get('/pertanyaan',                    [PertanyaanController::class, 'index'])->name('pertanyaan.index');
    Route::get('/pertanyaan/create',             [PertanyaanController::class, 'create'])->name('pertanyaan.create');
    Route::post('/pertanyaan',                   [PertanyaanController::class, 'store'])->name('pertanyaan.store');
    Route::get('/pertanyaan/{pertanyaan}/edit',  [PertanyaanController::class, 'edit'])->name('pertanyaan.edit');
    Route::put('/pertanyaan/{pertanyaan}',       [PertanyaanController::class, 'update'])->name('pertanyaan.update');
    Route::delete('/pertanyaan/{pertanyaan}',    [PertanyaanController::class, 'destroy'])->name('pertanyaan.destroy');

    // Verifikasi Pembayaran
    Route::get(
        'verifikasi-pembayaran',
        [VerifikasiPembayaranController::class, 'index']
    )->name('verifikasi-pembayaran.index');

    Route::get(
        'verifikasi-pembayaran/{verifikasiPembayaran}',
        [VerifikasiPembayaranController::class, 'show']
    )
        ->name('verifikasi-pembayaran.show');

    Route::post(
        'verifikasi-pembayaran/{verifikasiPembayaran}/setujui',
        [VerifikasiPembayaranController::class, 'setujui']
    )
        ->name('verifikasi-pembayaran.setujui');

    Route::post(
        'verifikasi-pembayaran/{verifikasiPembayaran}/tolak',
        [VerifikasiPembayaranController::class, 'tolak']
    )
        ->name('verifikasi-pembayaran.tolak');

    Route::delete(
        'verifikasi-pembayaran/{verifikasiPembayaran}',
        [VerifikasiPembayaranController::class, 'destroy']
    )
        ->name('verifikasi-pembayaran.destroy');
});
    // Dashboard & Logout
// Logout
