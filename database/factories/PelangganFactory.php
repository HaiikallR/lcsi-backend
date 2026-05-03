<?php

namespace Database\Factories;

use App\Models\Pelanggan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Pelanggan>
 */
class PelangganFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verifikasi' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'no_hp' => fake()->phoneNumber(),
            'alamat' => fake()->address(),
            'paket_langganan' => fake()->randomElement(['Basic', 'Silver', 'Gold']),
            'status' => fake()->randomElement(['aktif', 'tidak aktif']),
        ];
    }

    public function aktif(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'aktif',
        ]);
    }

    public function tidakAktif(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'tidak aktif',
        ]);
    }
}
