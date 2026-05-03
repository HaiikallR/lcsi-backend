<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Pengeluaran;
use App\Models\Tiket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pengeluaran>
 */
class PengeluaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tiket = Tiket::factory()->create();

        return [
            'id_tiket' => $tiket->id,
            'id_teknisi' => $tiket->id_teknisi,
            'judul' => 'Ongkos teknisi tiket #'.$tiket->id,
            'jumlah' => $tiket->ongkos_teknisi,
            'kategori' => 'ongkos teknisi',
            'bulan' => now()->translatedFormat('F'),
            'tahun' => (string) now()->year,
        ];
    }
}
