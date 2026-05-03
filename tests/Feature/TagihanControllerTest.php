<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Pelanggan;
use App\Models\Tagihan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TagihanControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_tidak_bisa_mengakses_list_tagihan(): void
    {
        $response = $this->getJson('/api/tagihan');

        $response->assertStatus(401);
    }

    public function test_admin_dapat_menambah_tagihan(): void
    {
        Sanctum::actingAs(Admin::factory()->create());
        $pelanggan = Pelanggan::factory()->create();

        $response = $this->postJson('/api/tagihan', [
            'id_pelanggan' => $pelanggan->id,
            'jumlah' => 275000,
            'bulan' => 'Mei',
            'tahun' => '2026',
            'catatan' => 'Tagihan internet bulanan',
            'status' => 'menunggu',
            'tanggal_bayar' => null,
            'tanggal_verifikasi' => null,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.jumlah', 275000);
    }

    public function test_store_tagihan_validasi_gagal(): void
    {
        Sanctum::actingAs(Admin::factory()->create());

        $response = $this->postJson('/api/tagihan', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['id_pelanggan', 'jumlah', 'bulan', 'tahun']);
    }

    public function test_admin_dapat_melihat_list_tagihan(): void
    {
        Sanctum::actingAs(Admin::factory()->create());
        Tagihan::factory()->count(2)->create();

        $response = $this->getJson('/api/tagihan');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'links', 'meta']);
    }
}
