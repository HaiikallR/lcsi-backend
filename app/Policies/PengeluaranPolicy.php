<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Pengeluaran;

class PengeluaranPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return true;
    }

    public function view(Admin $admin, Pengeluaran $pengeluaran): bool
    {
        return true;
    }

    public function create(Admin $admin): bool
    {
        return true;
    }

    public function update(Admin $admin, Pengeluaran $pengeluaran): bool
    {
        return true;
    }

    public function delete(Admin $admin, Pengeluaran $pengeluaran): bool
    {
        return true;
    }

    public function restore(Admin $admin, Pengeluaran $pengeluaran): bool
    {
        return true;
    }

    public function forceDelete(Admin $admin, Pengeluaran $pengeluaran): bool
    {
        return true;
    }
}
