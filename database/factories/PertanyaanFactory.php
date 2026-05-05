<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PertanyaanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pertanyaan' => $this->faker->sentence(),
            'jawaban' => $this->faker->paragraph(5),
            'kategori' => $this->faker->randomElement(['Teknis', 'Billing', 'Umum', 'Layanan']),
        ];
    }
}
