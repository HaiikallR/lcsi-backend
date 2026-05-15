<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePelangganRequest;
use App\Http\Requests\UpdatePelangganRequest;
use App\Http\Requests\UpdateFcmTokenRequest;
use App\Http\Resources\PelangganCollection;
use App\Http\Resources\PelangganResource;
use App\Models\Pelanggan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(): PelangganCollection
    {
        $this->authorize('viewAny', Pelanggan::class);

        return new PelangganCollection(Pelanggan::orderByDesc('id')->paginate(10));
    }

    public function store(StorePelangganRequest $request): JsonResponse
    {
        $this->authorize('create', Pelanggan::class);

        $pelanggan = Pelanggan::create($request->validated());

        return (new PelangganResource($pelanggan))->response()->setStatusCode(201);
    }

    public function show(Pelanggan $pelanggan): PelangganResource
    {
        $this->authorize('view', $pelanggan);

        return new PelangganResource($pelanggan);
    }

    public function update(UpdatePelangganRequest $request, Pelanggan $pelanggan): PelangganResource
    {
        $this->authorize('update', $pelanggan);

        $data = $request->validated();

        // Hapus password dari update jika tidak diisi
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $pelanggan->update($data);

        return new PelangganResource($pelanggan);
    }

    /**
     * PUT /api/pelanggan/{pelanggan}/fcm-token - Update FCM token
     * Client mengirim FCM token setelah login atau ketika token baru diperoleh
     */
    public function updateFcmToken(UpdateFcmTokenRequest $request, Pelanggan $pelanggan): PelangganResource
    {
        $pelanggan->update([
            'device_token' => $request->validated('device_token'),
        ]);

        return new PelangganResource($pelanggan);
    }

    public function destroy(Pelanggan $pelanggan): Response
    {
        $this->authorize('delete', $pelanggan);

        Pelanggan::destroy($pelanggan->id);

        return response()->noContent();
    }

    public function dashboard(Request $request)
    {
        $pelanggan = $request->user();

        // Hitung tagihan
        $tagihanBelumBayar = \App\Models\Tagihan::query()
            ->where('id_pelanggan', $pelanggan->id)
            ->where('status', 'belum bayar')
            ->sum('jumlah');

        $tagihanMenunggu = \App\Models\Tagihan::query()
            ->where('id_pelanggan', $pelanggan->id)
            ->where('status', 'menunggu')
            ->sum('jumlah');

        $totalTagihanBelumLunas = \App\Models\Tagihan::query()
            ->where('id_pelanggan', $pelanggan->id)
            ->whereIn('status', ['belum bayar', 'menunggu'])
            ->count();

        $tagihanTerbaru = \App\Models\Tagihan::query()
            ->where('id_pelanggan', $pelanggan->id)
            ->orderByDesc('id')
            ->take(3)
            ->get(['id', 'bulan', 'tahun', 'jumlah', 'status']);

        return response()->json([
            'pelanggan' => [
                'id'              => $pelanggan->id,
                'nama'            => $pelanggan->nama,
                'paket_langganan' => $pelanggan->paket_langganan,
                'status'          => $pelanggan->status,
            ],
            'tagihan' => [
                'total_belum_bayar'        => $tagihanBelumBayar,
                'total_menunggu_verifikasi' => $tagihanMenunggu,
                'jumlah_tagihan_aktif'     => $totalTagihanBelumLunas,
                'terbaru'                  => $tagihanTerbaru,
            ],
        ]);
    }
}
