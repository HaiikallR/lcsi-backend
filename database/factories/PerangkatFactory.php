<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Pelanggan;
use App\Models\Perangkat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Perangkat>
 */
class PerangkatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_perangkat' => fake()->words(2, true),
            'merk' => fake()->randomElement(['Cisco', 'MikroTik', 'TP-Link', 'Ubiquiti']),
            'serial_number' => strtoupper(fake()->unique()->bothify('SN-#####??')),
            'status' => fake()->randomElement(['tersedia', 'digunakan', 'perbaikan']),
            'id_pelanggan' => Pelanggan::factory(),
        ];
    }

    public function tersedia(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'tersedia',
        ]);
    }

    public function digunakan(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'digunakan',
        ]);
    }

    public function perbaikan(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'perbaikan',
        ]);
    }
}
