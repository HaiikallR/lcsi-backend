<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Pertanyaan;
use Illuminate\Database\Seeder;

class PertanyaanSeeder extends Seeder
{
    public function run(): void
    {
        Pertanyaan::factory(10)->create();
    }
}
