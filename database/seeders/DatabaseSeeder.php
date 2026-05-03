<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AdminSeeder::class);
        $this->call(PelangganSeeder::class);
        $this->call(PerangkatSeeder::class);
        $this->call(TeknisiSeeder::class);
        $this->call(TiketSeeder::class);
        $this->call(PengeluaranSeeder::class);
        $this->call(PemasukanSeeder::class);
        $this->call(PermintaanUpgradeSeeder::class);
        $this->call(TagihanSeeder::class);
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
