<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\PermintaanUpgrade;

class PermintaanUpgradePolicy
{
    public function viewAny(Admin $admin): bool
    {
        return true;
    }

    public function view(Admin $admin, PermintaanUpgrade $permintaanUpgrade): bool
    {
        return true;
    }

    public function create(Admin $admin): bool
    {
        return true;
    }

    public function update(Admin $admin, PermintaanUpgrade $permintaanUpgrade): bool
    {
        return true;
    }

    public function delete(Admin $admin, PermintaanUpgrade $permintaanUpgrade): bool
    {
        return true;
    }

    public function restore(Admin $admin, PermintaanUpgrade $permintaanUpgrade): bool
    {
        return true;
    }

    public function forceDelete(Admin $admin, PermintaanUpgrade $permintaanUpgrade): bool
    {
        return true;
    }
}
