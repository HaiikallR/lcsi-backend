<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Pelanggan;
use App\Models\PermintaanUpgrade;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PermintaanUpgradeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_tidak_bisa_mengakses_list_permintaan_upgrade(): void
    {
        $response = $this->getJson('/api/permintaan-upgrade');

        $response->assertStatus(401);
    }

    public function test_admin_dapat_menambah_permintaan_upgrade(): void
    {
        Sanctum::actingAs(Admin::factory()->create());
        $pelanggan = Pelanggan::factory()->create();

        $response = $this->postJson('/api/permintaan-upgrade', [
            'id_pelanggan' => $pelanggan->id,
            'paket_lama' => 'Basic',
            'paket_baru' => 'Gold',
            'harga_baru' => 450000,
            'status' => 'menunggu',
            'disetujui_pada' => null,
            'ditolak_pada' => null,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.paket_baru', 'Gold');
    }

    public function test_store_permintaan_upgrade_validasi_gagal(): void
    {
        Sanctum::actingAs(Admin::factory()->create());

        $response = $this->postJson('/api/permintaan-upgrade', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['id_pelanggan', 'paket_lama', 'paket_baru', 'harga_baru']);
    }

    public function test_admin_dapat_melihat_list_permintaan_upgrade(): void
    {
        Sanctum::actingAs(Admin::factory()->create());
        PermintaanUpgrade::factory()->count(2)->create();

        $response = $this->getJson('/api/permintaan-upgrade');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'links', 'meta']);
    }
}
