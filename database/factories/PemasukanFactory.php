<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Pelanggan;
use App\Models\Pemasukan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pemasukan>
 */
class PemasukanFactory extends Factory
{
    protected $model = Pemasukan::class;

    public function definition(): array
    {
        return [
            'id_pelanggan' => Pelanggan::factory(),
            'jenis_pemasukan' => fake()->randomElement(['Tagihan Bulanan', 'Pasang Baru', 'Upgrade Paket']),
            'jumlah_bayar' => fake()->numberBetween(100000, 1000000),
            'metode_bayar' => fake()->randomElement(['Transfer Bank', 'Tunai', 'QRIS']),
            'bukti_bayar' => fake()->optional()->imageUrl(),
            'keterangan' => fake()->optional()->sentence(),
            'status' => fake()->randomElement(['lunas', 'menunggu']),
            'bulan_tagihan' => fake()->monthName(),
            'tahun_tagihan' => (string) fake()->numberBetween((int) date('Y') - 1, (int) date('Y') + 1),
            'tanggal_bayar' => fake()->optional()->dateTimeThisYear(),
        ];
    }
}
