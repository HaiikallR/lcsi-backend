<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagihanCollection;
use App\Http\Resources\TagihanResource;
use App\Models\Tagihan;
use App\Http\Requests\StoretagihanRequest;
use App\Http\Requests\UpdatetagihanRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class TagihanController extends Controller
{
    public function index(): TagihanCollection
    {
        $this->authorize('viewAny', Tagihan::class);

        return new TagihanCollection(
            Tagihan::with('pelanggan:id,nama,email')
                ->orderByDesc('id')
                ->paginate(10)
        );
    }

    public function store(StoretagihanRequest $request): JsonResponse
    {
        $this->authorize('create', Tagihan::class);

        $tagihan = Tagihan::create($request->validated());

        return (new TagihanResource($tagihan->load('pelanggan:id,nama,email')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Tagihan $tagihan): TagihanResource
    {
        $this->authorize('view', $tagihan);

        return new TagihanResource($tagihan->load('pelanggan:id,nama,email'));
    }

    public function update(UpdatetagihanRequest $request, Tagihan $tagihan): TagihanResource
    {
        $this->authorize('update', $tagihan);

        $tagihan->update($request->validated());

        return new TagihanResource($tagihan->load('pelanggan:id,nama,email'));
    }

    public function destroy(Tagihan $tagihan): Response
    {
        $this->authorize('delete', $tagihan);

        Tagihan::destroy($tagihan->id);

        return response()->noContent();
    }
}
