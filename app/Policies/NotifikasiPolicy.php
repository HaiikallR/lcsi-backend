<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Pelanggan;
use App\Models\Notifikasi;

class NotifikasiPolicy
{
    public function viewAny(Pelanggan $pelanggan): bool
    {
        return true;
    }

    public function view(Pelanggan $pelanggan, Notifikasi $notifikasi): bool
    {
        return true;
    }

    public function create(Pelanggan $pelanggan): bool
    {
        return true;
    }

    public function update(Pelanggan $pelanggan, Notifikasi $notifikasi): bool
    {
        return true;
    }

    public function delete(Pelanggan $pelanggan, Notifikasi $notifikasi): bool
    {
        return true;
    }

    public function restore(Pelanggan $pelanggan, Notifikasi $notifikasi): bool
    {
        return true;
    }

    public function forceDelete(Pelanggan $pelanggan, Notifikasi $notifikasi): bool
    {
        return true;
    }
}
