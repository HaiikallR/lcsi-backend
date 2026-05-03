<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Tagihan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagihanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tagihan::factory()->count(10)->create();
    }
}
