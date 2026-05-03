<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Http\Resources\AdminCollection;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AdminController extends Controller
{
    public function index(): AdminCollection
    {
        $this->authorize('viewAny', Admin::class);

        return new AdminCollection(Admin::orderByDesc('id')->paginate(2));
    }

    public function store(StoreAdminRequest $request): JsonResponse
    {
        $this->authorize('create', Admin::class);

        $admin = Admin::create($request->validated());

        return (new AdminResource($admin))->response()->setStatusCode(201);
    }

    public function show(Admin $admin): AdminResource
    {
        $this->authorize('view', $admin);

        return new AdminResource($admin);
    }

    public function update(UpdateAdminRequest $request, Admin $admin): AdminResource
    {
        $this->authorize('update', $admin);

        $data = $request->validated();

        // Hapus password dari update jika tidak diisi
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $admin->update($data);

        return new AdminResource($admin);
    }

    public function destroy(Admin $admin): Response
    {
        $this->authorize('delete', $admin);

        Admin::destroy($admin->id);

        return response()->noContent();
    }
}
