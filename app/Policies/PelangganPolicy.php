<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Pelanggan;

class PelangganPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return true;
    }

    public function view(Admin $admin, Pelanggan $pelanggan): bool
    {
        return true;
    }

    public function create(Pelanggan $pelanggan): bool
    {
        return true;
    }

    public function update(Admin $admin, Pelanggan $pelanggan): bool
    {
        return true;
    }

    public function delete(Admin $admin, Pelanggan $pelanggan): bool
    {
        return true;
    }

    public function restore(Admin $admin, Pelanggan $pelanggan): bool
    {
        return true;
    }

    public function forceDelete(Admin $admin, Pelanggan $pelanggan): bool
    {
        return true;
    }
}
