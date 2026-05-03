<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTiketRequest;
use App\Http\Requests\UpdateTiketRequest;
use App\Http\Resources\MessageResource;
use App\Http\Resources\TiketCollection;
use App\Http\Resources\TiketResource;
use App\Http\Resources\WhatsappTicketResource;
use App\Models\Tiket;
use App\Services\TiketService;
use App\Services\WhatsappTicketMessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class TiketController extends Controller
{
    public function __construct(private readonly TiketService $tiketService) {}

    public function index(): TiketCollection
    {
        $this->authorize('viewAny', Tiket::class);

        return new TiketCollection(
            Tiket::with(['pelanggan:id,nama,email', 'teknisi:id,nama,no_hp,status', 'pengeluaran:id,id_tiket,judul,jumlah'])
                ->orderByDesc('id')
                ->paginate(10)
        );
    }

    public function store(StoreTiketRequest $request): JsonResponse
    {
        $this->authorize('create', Tiket::class);

        $data = $this->tiketService->normalizeData($request->validated());
        $data['status'] = 'in progress';
        $data['tanggal_selesai'] = null;

        $tiket = Tiket::create($data);

        return (new TiketResource($tiket->load(['pelanggan:id,nama,email', 'teknisi:id,nama,no_hp,status'])))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Tiket $tiket): TiketResource
    {
        $this->authorize('view', $tiket);

        return new TiketResource($tiket->load(['pelanggan:id,nama,email', 'teknisi:id,nama,no_hp,status', 'pengeluaran:id,id_tiket,judul,jumlah']));
    }

    public function update(UpdateTiketRequest $request, Tiket $tiket): TiketResource
    {
        $this->authorize('update', $tiket);

        $tiket->update($this->tiketService->normalizeData($request->validated()));

        return new TiketResource($tiket->load(['pelanggan:id,nama,email', 'teknisi:id,nama,no_hp,status', 'pengeluaran:id,id_tiket,judul,jumlah']));
    }

    public function destroy(Tiket $tiket): Response
    {
        $this->authorize('delete', $tiket);

        Tiket::destroy($tiket->id);

        return response()->noContent();
    }

    public function kirimWhatsapp(Tiket $tiket): WhatsappTicketResource
    {
        $this->authorize('view', $tiket);

        $tiket->load(['pelanggan:id,nama,email', 'teknisi:id,nama,no_hp,status']);

        $payload = app(WhatsappTicketMessageService::class)->buildPayload($tiket);

        return new WhatsappTicketResource($payload);
    }

    public function selesai(Tiket $tiket): MessageResource
    {
        $this->authorize('update', $tiket);

        $this->tiketService->selesai($tiket);

        return new MessageResource([
            'pesan' => 'Tiket berhasil diselesaikan dan pengeluaran dibuat.',
        ]);
    }
}
