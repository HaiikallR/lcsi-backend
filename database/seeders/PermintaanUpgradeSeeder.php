<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PermintaanUpgrade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermintaanUpgradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PermintaanUpgrade::factory()->count(8)->create();
    }
}
