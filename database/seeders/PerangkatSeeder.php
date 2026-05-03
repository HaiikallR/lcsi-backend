<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use App\Models\Perangkat;
use Illuminate\Database\Seeder;

class PerangkatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pelangganIds = Pelanggan::query()->pluck('id');

        if ($pelangganIds->isEmpty()) {
            $pelangganIds = collect([Pelanggan::factory()->create()->id]);
        }

        foreach ($pelangganIds as $pelangganId) {
            Perangkat::factory()
                ->state(fn () => ['id_pelanggan' => $pelangganId])
                ->create();
        }
    }
}
