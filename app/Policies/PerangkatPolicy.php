<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Perangkat;

class PerangkatPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return true;
    }

    public function view(Admin $admin, Perangkat $perangkat): bool
    {
        return $admin->id === $perangkat->id_admin;
    }

    public function create(Admin $admin): bool
    {
        return true;
    }

    public function update(Admin $admin, Perangkat $perangkat): bool
    {
        return $admin->id === $perangkat->id_admin;
    }

    public function delete(Admin $admin, Perangkat $perangkat): bool
    {
        return $admin->id === $perangkat->id_admin;
    }

    public function restore(Admin $admin, Perangkat $perangkat): bool
    {
        return $admin->id === $perangkat->id_admin;
    }

    public function forceDelete(Admin $admin, Perangkat $perangkat): bool
    {
        return $admin->id === $perangkat->id_admin;
    }
}
