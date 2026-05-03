<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePerangkatRequest;
use App\Http\Requests\UpdatePerangkatRequest;
use App\Http\Resources\PerangkatCollection;
use App\Http\Resources\PerangkatResource;
use App\Models\Perangkat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PerangkatController extends Controller
{
    public function index(Request $request): PerangkatCollection
    {
        $this->authorize('viewAny', Perangkat::class);

        return new PerangkatCollection(
            Perangkat::with('pelanggan:id,nama,email')
                ->orderByDesc('id')
                ->paginate(10)
        );
    }

    public function store(StorePerangkatRequest $request): JsonResponse
    {
        $this->authorize('create', Perangkat::class);

        $perangkat = Perangkat::create([
            ...$request->validated(),
            'id_pelanggan' => (int) $request->id_pelanggan,
        ]);

        return (new PerangkatResource($perangkat->load('pelanggan:id,nama,email')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Perangkat $perangkat): PerangkatResource
    {
        $this->authorize('view', $perangkat);

        return new PerangkatResource($perangkat->load('pelanggan:id,nama,email'));
    }

    public function update(UpdatePerangkatRequest $request, Perangkat $perangkat): PerangkatResource
    {
        $this->authorize('update', $perangkat);

        $perangkat->update($request->validated());

        return new PerangkatResource($perangkat->load('pelanggan:id,nama,email'));
    }

    public function destroy(Perangkat $perangkat): Response
    {
        $this->authorize('delete', $perangkat);

        Perangkat::destroy($perangkat->id);

        return response()->noContent();
    }
}
