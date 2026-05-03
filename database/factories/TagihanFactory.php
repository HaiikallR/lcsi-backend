<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Pelanggan;
use App\Models\Tagihan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tagihan>
 */
class TagihanFactory extends Factory
{
    protected $model = Tagihan::class;

    public function definition(): array
    {
        $status = fake()->randomElement(['belum bayar', 'menunggu', 'sudah bayar']);

        return [
            'id_pelanggan' => Pelanggan::factory(),
            'jumlah' => fake()->numberBetween(100000, 1000000),
            'bulan' => fake()->monthName(),
            'tahun' => (string) fake()->numberBetween((int) date('Y') - 1, (int) date('Y') + 1),
            'catatan' => fake()->optional()->sentence(),
            'status' => $status,
            'tanggal_bayar' => $status === 'sudah bayar' ? now() : null,
            'tanggal_verifikasi' => $status === 'sudah bayar' ? now() : null,
        ];
    }
}
