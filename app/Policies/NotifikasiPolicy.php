<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Notifikasi;

class NotifikasiPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return true;
    }

    public function view(Admin $admin, Notifikasi $notifikasi): bool
    {
        return true;
    }

    public function create(Admin $admin): bool
    {
        return true;
    }

    public function update(Admin $admin, Notifikasi $notifikasi): bool
    {
        return true;
    }

    public function delete(Admin $admin, Notifikasi $notifikasi): bool
    {
        return true;
    }

    public function restore(Admin $admin, Notifikasi $notifikasi): bool
    {
        return true;
    }

    public function forceDelete(Admin $admin, Notifikasi $notifikasi): bool
    {
        return true;
    }
}
