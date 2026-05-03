<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Pelanggan;
use App\Models\PermintaanUpgrade;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PermintaanUpgrade>
 */
class PermintaanUpgradeFactory extends Factory
{
    protected $model = PermintaanUpgrade::class;

    public function definition(): array
    {
        $status = fake()->randomElement(['menunggu', 'disetujui', 'ditolak']);

        return [
            'id_pelanggan' => Pelanggan::factory(),
            'paket_lama' => fake()->randomElement(['Basic', 'Silver']),
            'paket_baru' => fake()->randomElement(['Silver', 'Gold']),
            'harga_baru' => (string) fake()->numberBetween(200000, 600000),
            'status' => $status,
            'disetujui_pada' => $status === 'disetujui' ? now() : null,
            'ditolak_pada' => $status === 'ditolak' ? now() : null,
        ];
    }
}
