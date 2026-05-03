<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Teknisi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TeknisiControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized_mendapatkan_401_saat_akses_teknisi(): void
    {
        $response = $this->getJson('/api/teknisi');

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

    public function test_admin_dapat_melihat_list_teknisi(): void
    {
        Sanctum::actingAs(Admin::factory()->create());
        Teknisi::factory()->count(3)->create();

        $response = $this->getJson('/api/teknisi');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'links',
                'meta',
            ]);
    }

    public function test_admin_dapat_menambah_teknisi(): void
    {
        Sanctum::actingAs(Admin::factory()->create());

        $payload = [
            'nama' => 'Teknisi A',
            'no_hp' => '081234567890',
            'status' => 'aktif',
        ];

        $response = $this->postJson('/api/teknisi', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('data.nama', 'Teknisi A')
            ->assertJsonPath('data.status', 'aktif');

        $this->assertDatabaseHas('teknisis', [
            'nama' => 'Teknisi A',
            'no_hp' => '081234567890',
        ]);
    }

    public function test_validasi_store_teknisi_gagal_mengembalikan_422(): void
    {
        Sanctum::actingAs(Admin::factory()->create());

        $response = $this->postJson('/api/teknisi', [
            'nama' => '',
            'no_hp' => '',
            'status' => 'invalid',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nama', 'no_hp', 'status']);
    }

    public function test_admin_dapat_update_teknisi(): void
    {
        Sanctum::actingAs(Admin::factory()->create());

        $teknisi = Teknisi::factory()->create([
            'status' => 'aktif',
        ]);

        $response = $this->putJson('/api/teknisi/'.$teknisi->id, [
            'nama' => $teknisi->nama,
            'no_hp' => $teknisi->no_hp,
            'status' => 'siap',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.status', 'siap');

        $this->assertDatabaseHas('teknisis', [
            'id' => $teknisi->id,
            'status' => 'siap',
        ]);
    }

    public function test_admin_dapat_hapus_teknisi(): void
    {
        Sanctum::actingAs(Admin::factory()->create());

        $teknisi = Teknisi::factory()->create();

        $response = $this->deleteJson('/api/teknisi/'.$teknisi->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('teknisis', [
            'id' => $teknisi->id,
        ]);
    }
}
