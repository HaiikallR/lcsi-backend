<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;

class AdminPolicy
{
    public function viewAny(Admin $user): bool
    {
        return true;
    }

    public function view(Admin $user, Admin $admin): bool
    {
        return true;
    }

    public function create(Admin $user): bool
    {
        return true;
    }

    public function update(Admin $user, Admin $admin): bool
    {
        return true;
    }

    public function delete(Admin $user, Admin $admin): bool
    {
        // Admin tidak boleh menghapus dirinya sendiri
        return $user->id !== $admin->id;
    }

    public function restore(Admin $user, Admin $admin): bool
    {
        return true;
    }

    public function forceDelete(Admin $user, Admin $admin): bool
    {
        return true;
    }
}
