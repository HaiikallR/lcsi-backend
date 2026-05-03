<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePengeluaranRequest;
use App\Http\Requests\UpdatePengeluaranRequest;
use App\Http\Resources\PengeluaranCollection;
use App\Http\Resources\PengeluaranResource;
use App\Models\Pengeluaran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PengeluaranController extends Controller
{
    public function index(): PengeluaranCollection
    {
        $this->authorize('viewAny', Pengeluaran::class);

        return new PengeluaranCollection(
            Pengeluaran::with(['tiket:id,jenis_pekerjaan,status', 'teknisi:id,nama'])
                ->orderByDesc('id')
                ->paginate(10)
        );
    }

    public function store(StorePengeluaranRequest $request): JsonResponse
    {
        $this->authorize('create', Pengeluaran::class);

        $pengeluaran = Pengeluaran::create($request->validated());

        return (new PengeluaranResource($pengeluaran->load(['tiket:id,jenis_pekerjaan,status', 'teknisi:id,nama'])))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Pengeluaran $pengeluaran): PengeluaranResource
    {
        $this->authorize('view', $pengeluaran);

        return new PengeluaranResource($pengeluaran->load(['tiket:id,jenis_pekerjaan,status', 'teknisi:id,nama']));
    }

    public function update(UpdatePengeluaranRequest $request, Pengeluaran $pengeluaran): PengeluaranResource
    {
        $this->authorize('update', $pengeluaran);

        $pengeluaran->update($request->validated());

        return new PengeluaranResource($pengeluaran->load(['tiket:id,jenis_pekerjaan,status', 'teknisi:id,nama']));
    }

    public function destroy(Pengeluaran $pengeluaran): Response
    {
        $this->authorize('delete', $pengeluaran);

        Pengeluaran::destroy($pengeluaran->id);

        return response()->noContent();
    }
}
