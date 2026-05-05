<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Pertanyaan;

class PertanyaanPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return true;
    }

    public function view(Admin $admin, Pertanyaan $pertanyaan): bool
    {
        return true;
    }

    public function create(Admin $admin): bool
    {
        return true;
    }

    public function update(Admin $admin, Pertanyaan $pertanyaan): bool
    {
        return true;
    }

    public function delete(Admin $admin, Pertanyaan $pertanyaan): bool
    {
        return true;
    }

    public function restore(Admin $admin, Pertanyaan $pertanyaan): bool
    {
        return true;
    }

    public function forceDelete(Admin $admin, Pertanyaan $pertanyaan): bool
    {
        return true;
    }
}
