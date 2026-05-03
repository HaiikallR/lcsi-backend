<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeknisiRequest;
use App\Http\Requests\UpdateTeknisiRequest;
use App\Http\Resources\TeknisiCollection;
use App\Http\Resources\TeknisiResource;
use App\Models\Teknisi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class TeknisiController extends Controller
{
    public function index(): TeknisiCollection
    {
        $this->authorize('viewAny', Teknisi::class);

        return new TeknisiCollection(Teknisi::orderByDesc('id')->paginate(10));
    }

    public function store(StoreTeknisiRequest $request): JsonResponse
    {
        $this->authorize('create', Teknisi::class);

        $teknisi = Teknisi::create($request->validated());

        return (new TeknisiResource($teknisi))->response()->setStatusCode(201);
    }

    public function show(Teknisi $teknisi): TeknisiResource
    {
        $this->authorize('view', $teknisi);

        return new TeknisiResource($teknisi);
    }

    public function update(UpdateTeknisiRequest $request, Teknisi $teknisi): TeknisiResource
    {
        $this->authorize('update', $teknisi);

        $teknisi->update($request->validated());

        return new TeknisiResource($teknisi);
    }

    public function destroy(Teknisi $teknisi): Response
    {
        $this->authorize('delete', $teknisi);

        Teknisi::destroy($teknisi->id);

        return response()->noContent();
    }
}
