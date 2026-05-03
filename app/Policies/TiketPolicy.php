<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Tiket;

class TiketPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return true;
    }

    public function view(Admin $admin, Tiket $tiket): bool
    {
        return true;
    }

    public function create(Admin $admin): bool
    {
        return true;
    }

    public function update(Admin $admin, Tiket $tiket): bool
    {
        return true;
    }

    public function delete(Admin $admin, Tiket $tiket): bool
    {
        return true;
    }

    public function restore(Admin $admin, Tiket $tiket): bool
    {
        return true;
    }

    public function forceDelete(Admin $admin, Tiket $tiket): bool
    {
        return true;
    }
}
