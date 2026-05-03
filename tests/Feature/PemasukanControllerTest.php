<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Pelanggan;
use App\Models\Pemasukan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PemasukanControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_tidak_bisa_mengakses_list_pemasukan(): void
    {
        $response = $this->getJson('/api/pemasukan');

        $response->assertStatus(401);
    }

    public function test_admin_dapat_menambah_pemasukan(): void
    {
        Sanctum::actingAs(Admin::factory()->create());
        $pelanggan = Pelanggan::factory()->create();

        $response = $this->postJson('/api/pemasukan', [
            'id_pelanggan' => $pelanggan->id,
            'jenis_pemasukan' => 'Tagihan Bulanan',
            'jumlah_bayar' => 300000,
            'metode_bayar' => 'Transfer Bank',
            'bukti_bayar' => null,
            'keterangan' => 'Pembayaran Mei',
            'status' => 'lunas',
            'bulan_tagihan' => 'Mei',
            'tahun_tagihan' => '2026',
            'tanggal_bayar' => now()->toDateString(),
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.jumlah_bayar', 300000);
    }

    public function test_store_pemasukan_validasi_gagal_tanpa_field_wajib(): void
    {
        Sanctum::actingAs(Admin::factory()->create());

        $response = $this->postJson('/api/pemasukan', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['id_pelanggan', 'jenis_pemasukan', 'jumlah_bayar']);
    }

    public function test_admin_dapat_melihat_list_pemasukan(): void
    {
        Sanctum::actingAs(Admin::factory()->create());
        Pemasukan::factory()->count(2)->create();

        $response = $this->getJson('/api/pemasukan');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'links', 'meta']);
    }
}
