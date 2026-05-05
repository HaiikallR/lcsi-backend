<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Notifikasi;
use Illuminate\Database\Seeder;

class NotifikasiSeeder extends Seeder
{
    public function run(): void
    {
        Notifikasi::factory(10)->create();
    }
}
