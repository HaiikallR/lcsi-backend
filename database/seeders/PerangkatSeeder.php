<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Perangkat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PerangkatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminIds = Admin::query()->pluck('id');

        if ($adminIds->isEmpty()) {
            $adminIds = collect([Admin::factory()->create()->id]);
        }

        Perangkat::factory(30)
            ->state(fn () => ['id_admin' => $adminIds->random()])
            ->create();
    }
}
