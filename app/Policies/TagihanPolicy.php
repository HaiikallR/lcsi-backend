<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Tagihan;

class TagihanPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return true;
    }

    public function view(Admin $admin, Tagihan $tagihan): bool
    {
        return true;
    }

    public function create(Admin $admin): bool
    {
        return true;
    }

    public function update(Admin $admin, Tagihan $tagihan): bool
    {
        return true;
    }

    public function delete(Admin $admin, Tagihan $tagihan): bool
    {
        return true;
    }

    public function restore(Admin $admin, Tagihan $tagihan): bool
    {
        return true;
    }

    public function forceDelete(Admin $admin, Tagihan $tagihan): bool
    {
        return true;
    }
}
