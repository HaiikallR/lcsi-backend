<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PermintaanUpgrade;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PelangganUpgradeController extends Controller
{
    /**
     * GET /api/pelanggan/upgrade
     * Riwayat permintaan upgrade milik pelanggan
     */
    public function index(Request $request): JsonResponse
    {
        $pelanggan = $request->user();

        $upgrades = PermintaanUpgrade::query()
            ->where('id_pelanggan', $pelanggan->id)
            ->orderByDesc('id')
            ->get([
                'id',
                'paket_lama',
                'paket_baru',
                'harga_baru',
                'status',
                'catatan',
                'disetujui_pada',
                'ditolak_pada',
                'created_at',
            ]);

        return response()->json(['data' => $upgrades]);
    }

    /**
     * POST /api/pelanggan/upgrade
     * Ajukan permintaan upgrade paket
     */
    public function store(Request $request): JsonResponse
    {
        $pelanggan = $request->user();

        $request->validate([
            'paket_baru'  => ['required', 'string', 'max:100'],
            'harga_baru'  => ['required', 'numeric', 'min:0'],
        ]);

        // Cek apakah sudah ada permintaan yang masih menunggu
        $sudahAda = PermintaanUpgrade::query()
            ->where('id_pelanggan', $pelanggan->id)
            ->where('status', 'menunggu')
            ->exists();

        if ($sudahAda) {
            return response()->json([
                'message' => 'Kamu masih memiliki permintaan upgrade yang sedang menunggu persetujuan.',
            ], 422);
        }

        $upgrade = PermintaanUpgrade::create([
            'id_pelanggan' => $pelanggan->id,
            'paket_lama'   => $pelanggan->paket_langganan,
            'paket_baru'   => $request->paket_baru,
            'harga_baru'   => $request->harga_baru,
            'status'       => 'menunggu',
        ]);

        return response()->json([
            'message' => 'Permintaan upgrade berhasil dikirim.',
            'data'    => $upgrade,
        ], 201);
    }
}
