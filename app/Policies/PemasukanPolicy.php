<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Pemasukan;

class PemasukanPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return true;
    }

    public function view(Admin $admin, Pemasukan $pemasukan): bool
    {
        return true;
    }

    public function create(Admin $admin): bool
    {
        return true;
    }

    public function update(Admin $admin, Pemasukan $pemasukan): bool
    {
        return true;
    }

    public function delete(Admin $admin, Pemasukan $pemasukan): bool
    {
        return true;
    }

    public function restore(Admin $admin, Pemasukan $pemasukan): bool
    {
        return true;
    }

    public function forceDelete(Admin $admin, Pemasukan $pemasukan): bool
    {
        return true;
    }
}
