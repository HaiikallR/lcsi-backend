<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePelangganRequest;
use App\Http\Requests\UpdatePelangganRequest;
use App\Http\Resources\PelangganCollection;
use App\Http\Resources\PelangganResource;
use App\Models\Pelanggan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

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

    public function destroy(Pelanggan $pelanggan): Response
    {
        $this->authorize('delete', $pelanggan);

        Pelanggan::destroy($pelanggan->id);

        return response()->noContent();
    }
}
