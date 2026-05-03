<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Pelanggan;
use App\Models\Perangkat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PerangkatControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_tidak_login_mendapatkan_401_saat_akses_perangkat(): void
    {
        $response = $this->getJson('/api/perangkat');

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

    public function test_admin_dapat_melihat_list_perangkat(): void
    {
        $admin = Admin::factory()->create();
        Sanctum::actingAs($admin);

        Perangkat::factory()->count(2)->create();

        $response = $this->getJson('/api/perangkat');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'links',
                'meta',
            ]);
    }

    public function test_admin_dapat_menambah_perangkat_baru(): void
    {
        $admin = Admin::factory()->create();
        Sanctum::actingAs($admin);

        $pelanggan = Pelanggan::factory()->create();

        $payload = [
            'nama_perangkat' => 'Router Core',
            'merk' => 'MikroTik',
            'serial_number' => 'SN-ROUTER-001',
            'status' => 'tersedia',
            'id_pelanggan' => $pelanggan->id,
        ];

        $response = $this->postJson('/api/perangkat', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('data.nama_perangkat', 'Router Core')
            ->assertJsonPath('data.id_pelanggan', $pelanggan->id);

        $this->assertDatabaseHas('perangkats', [
            'serial_number' => 'SN-ROUTER-001',
            'id_pelanggan' => $pelanggan->id,
        ]);
    }

    public function test_validasi_store_perangkat_gagal_mengembalikan_422(): void
    {
        Sanctum::actingAs(Admin::factory()->create());

        $pelanggan = Pelanggan::factory()->create();
        Perangkat::factory()->create([
            'id_pelanggan' => $pelanggan->id,
        ]);

        $response = $this->postJson('/api/perangkat', [
            'nama_perangkat' => '',
            'merk' => '',
            'serial_number' => '',
            'status' => 'invalid',
            'id_pelanggan' => $pelanggan->id,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'nama_perangkat',
                'merk',
                'serial_number',
                'status',
                'id_pelanggan',
            ]);
    }

    public function test_satu_pelanggan_hanya_bisa_memiliki_satu_perangkat(): void
    {
        Sanctum::actingAs(Admin::factory()->create());

        $pelanggan = Pelanggan::factory()->create();
        Perangkat::factory()->create([
            'id_pelanggan' => $pelanggan->id,
        ]);

        $response = $this->postJson('/api/perangkat', [
            'nama_perangkat' => 'Switch Distribution',
            'merk' => 'Cisco',
            'serial_number' => 'SN-SW-NEW-001',
            'status' => 'tersedia',
            'id_pelanggan' => $pelanggan->id,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['id_pelanggan']);
    }

    public function test_admin_dapat_update_perangkat_milik_sendiri(): void
    {
        $admin = Admin::factory()->create();
        Sanctum::actingAs($admin);

        $pelanggan = Pelanggan::factory()->create();
        $perangkat = Perangkat::factory()->create([
            'id_pelanggan' => $pelanggan->id,
            'status' => 'tersedia',
        ]);

        $response = $this->putJson('/api/perangkat/'.$perangkat->id, [
            'nama_perangkat' => $perangkat->nama_perangkat,
            'merk' => $perangkat->merk,
            'serial_number' => $perangkat->serial_number,
            'status' => 'digunakan',
            'id_pelanggan' => $pelanggan->id,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.status', 'digunakan');

        $this->assertDatabaseHas('perangkats', [
            'id' => $perangkat->id,
            'status' => 'digunakan',
        ]);
    }

    public function test_admin_dapat_hapus_perangkat_milik_sendiri(): void
    {
        $admin = Admin::factory()->create();
        Sanctum::actingAs($admin);

        $perangkat = Perangkat::factory()->create([
            'id_pelanggan' => Pelanggan::factory()->create()->id,
        ]);

        $response = $this->deleteJson('/api/perangkat/'.$perangkat->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('perangkats', [
            'id' => $perangkat->id,
        ]);
    }
}
