<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNotifikasiRequest;
use App\Http\Requests\UpdateNotifikasiRequest;
use App\Http\Resources\NotifikasiCollection;
use App\Http\Resources\NotifikasiResource;
use App\Models\Notifikasi;
use App\Models\Pelanggan;
use App\Services\FcmNotificationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request; // Tambahkan import Request untuk method index
use Illuminate\Http\Response;

class NotifikasiController extends Controller
{
    public function __construct(private FcmNotificationService $fcmService) {}

    /**
     * GET /api/notifikasi - Daftar semua notifikasi milik pelanggan yang login
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $pelanggan = $request->user();

        $notifikasi = Notifikasi::query()->where('id_pelanggan', $pelanggan->id)
            ->orderByDesc('id')
            ->get();

        // ✅ Pastikan return JSON biasa bukan Resource
        // agar field name konsisten
        return response()->json([
            'data' => $notifikasi->map(fn($n) => [
                'id'           => $n->id,
                'id_pelanggan' => $n->id_pelanggan,
                'judul'        => $n->judul,
                'pesan'        => $n->pesan,
                'kategori'     => $n->kategori,
                // ✅ Pakai created_at standar Laravel
                'created_at'   => $n->created_at,
                'updated_at'   => $n->updated_at,
            ]),
        ]);
    }
    /**
     * POST /api/notifikasi - Buat notifikasi baru & kirim via FCM
     */
    public function store(StoreNotifikasiRequest $request): JsonResponse
    {
        // $this->authorize('create', Notifikasi::class);

        $validated = $request->validated();

        // 1. Validasi & cari pelanggan
        $pelanggan = Pelanggan::findOrFail($validated['id_pelanggan']);

        // 2. Buat record notifikasi
        $notifikasi = Notifikasi::create($validated);

        // 3. Kirim via FCM jika pelanggan punya device token
        if (!empty($pelanggan->device_token)) {
            try {
                $this->fcmService->kirim(
                    fcmToken: $pelanggan->device_token,
                    judul: $validated['judul'],
                    pesan: $validated['pesan'],
                    data: [
                        'kategori' => $validated['kategori'],
                        'notifikasi_id' => (string) $notifikasi->id,
                        'timestamp' => now()->toIso8601String(),
                    ]
                );
            } catch (Exception $e) {
                // Log FCM error tapi notifikasi tetap tersimpan di DB
                \Illuminate\Support\Facades\Log::error(
                    'FCM send failed: ' . $e->getMessage(),
                    ['notifikasi_id' => $notifikasi->id]
                );
            }
        }

        return (new NotifikasiResource($notifikasi->load('pelanggan')))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * GET /api/notifikasi/{notifikasi} - Detail notifikasi
     */
    public function show(Notifikasi $notifikasi): NotifikasiResource
    {
        // $this->authorize('view', $notifikasi);
        return new NotifikasiResource($notifikasi->load('pelanggan'));
    }

    /**
     * PUT /api/notifikasi/{notifikasi} - Update notifikasi
     */
    public function update(UpdateNotifikasiRequest $request, Notifikasi $notifikasi): NotifikasiResource
    {
        // $this->authorize('update', $notifikasi);
        $notifikasi->update($request->validated());

        return new NotifikasiResource($notifikasi->load('pelanggan'));
    }

    /**
     * DELETE /api/notifikasi/{notifikasi} - Hapus notifikasi
     */
    public function destroy(Notifikasi $notifikasi): Response
    {
        // $this->authorize('delete', $notifikasi);

        // Perbaikan: gunakan fungsi standar Eloquent delete()
        $notifikasi->delete($notifikasi->id);

        return response()->noContent();
    }
}
