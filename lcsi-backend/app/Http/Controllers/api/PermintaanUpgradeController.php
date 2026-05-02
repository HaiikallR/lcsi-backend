<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use App\Models\PermintaanUpgrade;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PermintaanUpgradeController extends Controller
{
    /**
     * Menampilkan daftar permintaan upgrade yang berstatus 'pending'.
     */
    public function index()
    {
        $requests = PermintaanUpgrade::where('status', 'pending')
            ->orderBy('timestamp', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $requests
        ]);
    }

    /**
     * Menyetujui Permintaan Upgrade.
     * Mengubah paket user dan status permintaan secara bersamaan.
     */
    public function approve($id)
    {
        return DB::transaction(function () use ($id) {
            $upgradeReq = PermintaanUpgrade::findOrFail($id);
            $user = Pelanggan::findOrFail($upgradeReq->user_id);

            // 1. Update paket di tabel users
            $user->update([
                'paket' => $upgradeReq->paket_baru
            ]);

            // 2. Update status permintaan menjadi success
            $upgradeReq->update([
                'status' => 'success',
                'approved_at' => Carbon::now()
            ]);

            // --- LOGIKA NOTIFIKASI ---
            // Jika user memiliki fcm_token, kirim notifikasi melalui service
            if ($user->fcm_token) {
                // notification_service->sendUpgradeSuccess($user->fcm_token, $upgradeReq->paket_baru);
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil disetujui & Notifikasi dikirim",
            ]);
        });
    }

    /**
     * Menolak Permintaan Upgrade.
     */
    public function reject($id)
    {
        $upgradeReq = PermintaanUpgrade::findOrFail($id);

        $upgradeReq->update([
            'status' => 'rejected',
            'rejected_at' => Carbon::now()
        ]);

        return response()->json([
            'success' => true,
            'message' => "Permintaan ditolak",
        ]);
    }
}
