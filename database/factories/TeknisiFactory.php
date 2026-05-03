<?php

namespace Database\Factories;

use App\Models\Teknisi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Teknisi>
 */
class TeknisiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'no_hp' => fake()->phoneNumber(),
            'status' => fake()->randomElement(['aktif', 'siap', 'tidak aktif']),
        ];
    }
}
