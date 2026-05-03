<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Pengeluaran;
use App\Models\Tiket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PengeluaranControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dapat_melihat_list_pengeluaran(): void
    {
        Sanctum::actingAs(Admin::factory()->create());
        Pengeluaran::factory()->count(2)->create();

        $response = $this->getJson('/api/pengeluaran');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'links', 'meta']);
    }

    public function test_admin_dapat_menambah_pengeluaran(): void
    {
        Sanctum::actingAs(Admin::factory()->create());
        $tiket = Tiket::factory()->create();

        $response = $this->postJson('/api/pengeluaran', [
            'id_tiket' => $tiket->id,
            'id_teknisi' => $tiket->id_teknisi,
            'judul' => 'Pembayaran teknisi',
            'jumlah' => 250000,
            'kategori' => 'ongkos teknisi',
            'bulan' => 'Mei',
            'tahun' => '2026',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.jumlah', 250000);
    }
}