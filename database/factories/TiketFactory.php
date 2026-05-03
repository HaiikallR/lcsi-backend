<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Pelanggan;
use App\Models\Teknisi;
use App\Models\Tiket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tiket>
 */
class TiketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_pelanggan' => Pelanggan::factory(),
            'id_teknisi' => Teknisi::factory(),
            'jenis_pekerjaan' => fake()->randomElement(['perbaikan/gangguan', 'relokasi', 'maintenance']),
            'calon_pelanggan_nama' => null,
            'calon_pelanggan_no_hp' => null,
            'calon_pelanggan_alamat' => null,
            'ongkos_teknisi' => fake()->numberBetween(50000, 300000),
            'status' => 'in progress',
            'tanggal_selesai' => null,
        ];
    }

    public function pasangBaru(): static
    {
        return $this->state(fn () => [
            'id_pelanggan' => null,
            'jenis_pekerjaan' => 'pasang baru',
            'calon_pelanggan_nama' => fake()->name(),
            'calon_pelanggan_no_hp' => fake()->phoneNumber(),
            'calon_pelanggan_alamat' => fake()->address(),
        ]);
    }
}
