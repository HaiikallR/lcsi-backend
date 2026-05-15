<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PertanyaanCollection;
use App\Http\Resources\PertanyaanResource;
use App\Models\Pertanyaan;
use App\Http\Requests\StorePertanyaanRequest;
use App\Http\Requests\UpdatePertanyaanRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class PertanyaanController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $pelanggan = $request->user();

        $pertanyaan = Pertanyaan::orderByDesc('id')->get();

        // ✅ Pastikan return JSON biasa bukan Resource
        // agar field name konsisten
        return response()->json([
            'data' => $pertanyaan->map(fn($p) => [
                'id'           => $p->id,
                'pertanyaan'   => $p->pertanyaan,
                'jawaban'     => $p->jawaban,
                'kategori'     => $p->kategori,
                // ✅ Pakai created_at standar Laravel
                'created_at'   => $p->created_at,
                'updated_at'   => $p->updated_at,
            ]),
        ]);
    }

    public function store(StorePertanyaanRequest $request): JsonResponse
    {
        $this->authorize('create', Pertanyaan::class);

        $pertanyaan = Pertanyaan::create($request->validated());

        return (new PertanyaanResource($pertanyaan))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Pertanyaan $pertanyaan): PertanyaanResource
    {
        $this->authorize('view', $pertanyaan);

        return new PertanyaanResource($pertanyaan);
    }

    public function update(UpdatePertanyaanRequest $request, Pertanyaan $pertanyaan): PertanyaanResource
    {
        $this->authorize('update', $pertanyaan);

        $pertanyaan->update($request->validated());

        return new PertanyaanResource($pertanyaan);
    }

    public function destroy(Pertanyaan $pertanyaan): Response
    {
        $this->authorize('delete', $pertanyaan);

        Pertanyaan::destroy($pertanyaan->id);

        return response()->noContent();
    }
}
