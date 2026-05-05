<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NotifikasiFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_pelanggan' => \App\Models\Pelanggan::factory(),
            'judul' => $this->faker->sentence(),
            'pesan' => $this->faker->paragraph(3),
            'kategori' => $this->faker->randomElement(['info', 'warning', 'error', 'success']),
        ];
    }
}
