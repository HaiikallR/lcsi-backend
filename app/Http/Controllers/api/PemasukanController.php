<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PemasukanCollection;
use App\Http\Resources\PemasukanResource;
use App\Models\Pemasukan;
use App\Http\Requests\StorepemasukanRequest;
use App\Http\Requests\UpdatepemasukanRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PemasukanController extends Controller
{
    public function index(): PemasukanCollection
    {
        $this->authorize('viewAny', Pemasukan::class);

        return new PemasukanCollection(
            Pemasukan::with('pelanggan:id,nama,email')
                ->orderByDesc('id')
                ->paginate(10)
        );
    }

    public function store(StorepemasukanRequest $request): JsonResponse
    {
        $this->authorize('create', Pemasukan::class);

        $pemasukan = Pemasukan::create($request->validated());

        return (new PemasukanResource($pemasukan->load('pelanggan:id,nama,email')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Pemasukan $pemasukan): PemasukanResource
    {
        $this->authorize('view', $pemasukan);

        return new PemasukanResource($pemasukan->load('pelanggan:id,nama,email'));
    }

    public function update(UpdatepemasukanRequest $request, Pemasukan $pemasukan): PemasukanResource
    {
        $this->authorize('update', $pemasukan);

        $pemasukan->update($request->validated());

        return new PemasukanResource($pemasukan->load('pelanggan:id,nama,email'));
    }

    public function destroy(Pemasukan $pemasukan): Response
    {
        $this->authorize('delete', $pemasukan);

        Pemasukan::destroy($pemasukan->id);

        return response()->noContent();
    }
}
