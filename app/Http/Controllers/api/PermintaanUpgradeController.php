<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermintaanUpgradeCollection;
use App\Http\Resources\PermintaanUpgradeResource;
use App\Models\PermintaanUpgrade;
use App\Http\Requests\Storepermintaan_upgradeRequest;
use App\Http\Requests\Updatepermintaan_upgradeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PermintaanUpgradeController extends Controller
{
    public function index(): PermintaanUpgradeCollection
    {
        $this->authorize('viewAny', PermintaanUpgrade::class);

        return new PermintaanUpgradeCollection(
            PermintaanUpgrade::with('pelanggan:id,nama,email,paket_langganan')
                ->orderByDesc('id')
                ->paginate(10)
        );
    }

    public function store(Storepermintaan_upgradeRequest $request): JsonResponse
    {
        $this->authorize('create', PermintaanUpgrade::class);

        $permintaanUpgrade = PermintaanUpgrade::create($request->validated());

        return (new PermintaanUpgradeResource($permintaanUpgrade->load('pelanggan:id,nama,email,paket_langganan')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(PermintaanUpgrade $permintaanUpgrade): PermintaanUpgradeResource
    {
        $this->authorize('view', $permintaanUpgrade);

        return new PermintaanUpgradeResource($permintaanUpgrade->load('pelanggan:id,nama,email,paket_langganan'));
    }

    public function update(Updatepermintaan_upgradeRequest $request, PermintaanUpgrade $permintaanUpgrade): PermintaanUpgradeResource
    {
        $this->authorize('update', $permintaanUpgrade);

        $permintaanUpgrade->update($request->validated());

        return new PermintaanUpgradeResource($permintaanUpgrade->load('pelanggan:id,nama,email,paket_langganan'));
    }

    public function destroy(PermintaanUpgrade $permintaanUpgrade): Response
    {
        $this->authorize('delete', $permintaanUpgrade);

        PermintaanUpgrade::destroy($permintaanUpgrade->id);

        return response()->noContent();
    }
}
