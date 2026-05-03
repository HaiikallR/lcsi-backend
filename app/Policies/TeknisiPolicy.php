<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Teknisi;

class TeknisiPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return true;
    }

    public function view(Admin $admin, Teknisi $teknisi): bool
    {
        return true;
    }

    public function create(Admin $admin): bool
    {
        return true;
    }

    public function update(Admin $admin, Teknisi $teknisi): bool
    {
        return true;
    }

    public function delete(Admin $admin, Teknisi $teknisi): bool
    {
        return true;
    }

    public function restore(Admin $admin, Teknisi $teknisi): bool
    {
        return true;
    }

    public function forceDelete(Admin $admin, Teknisi $teknisi): bool
    {
        return true;
    }
}
