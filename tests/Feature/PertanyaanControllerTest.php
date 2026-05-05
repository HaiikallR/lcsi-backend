<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Pertanyaan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PertanyaanControllerTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
    }

    public function test_admin_dapat_melihat_list_pertanyaan(): void
    {
        Sanctum::actingAs($this->admin);
        Pertanyaan::factory(5)->create();

        $response = $this->getJson('/api/pertanyaan');

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['*' => ['id', 'judul', 'isi', 'kategori', 'status', 'dibuat_pada']]]);
    }

    public function test_admin_dapat_membuat_pertanyaan_baru(): void
    {
        Sanctum::actingAs($this->admin);

        $data = [
            'judul' => 'Bagaimana cara mendaftar?',
            'isi' => 'Untuk mendaftar, silakan kunjungi halaman registrasi dan lengkapi form.',
            'kategori' => 'Umum',
            'status' => 'aktif',
        ];

        $response = $this->postJson('/api/pertanyaan', $data);

        $response->assertStatus(201)
            ->assertJsonPath('id', 1)
            ->assertJsonPath('judul', $data['judul'])
            ->assertJsonPath('status', 'aktif');

        $this->assertDatabaseHas('pertanyaans', ['judul' => $data['judul']]);
    }

    public function test_admin_dapat_melihat_detail_pertanyaan(): void
    {
        Sanctum::actingAs($this->admin);
        $pertanyaan = Pertanyaan::factory()->create();

        $response = $this->getJson("/api/pertanyaan/{$pertanyaan->id}");

        $response->assertStatus(200)
            ->assertJsonPath('id', $pertanyaan->id)
            ->assertJsonPath('judul', $pertanyaan->judul);
    }

    public function test_admin_dapat_mengupdate_pertanyaan(): void
    {
        Sanctum::actingAs($this->admin);
        $pertanyaan = Pertanyaan::factory()->create();

        $data = [
            'judul' => 'Judul pertanyaan yang diupdate',
            'isi' => 'Isi yang sudah diupdate',
            'kategori' => 'Teknis',
            'status' => 'tidak_aktif',
        ];

        $response = $this->putJson("/api/pertanyaan/{$pertanyaan->id}", $data);

        $response->assertStatus(200)
            ->assertJsonPath('judul', $data['judul'])
            ->assertJsonPath('status', 'tidak_aktif');

        $this->assertDatabaseHas('pertanyaans', ['judul' => $data['judul']]);
    }

    public function test_admin_dapat_menghapus_pertanyaan(): void
    {
        Sanctum::actingAs($this->admin);
        $pertanyaan = Pertanyaan::factory()->create();

        $response = $this->deleteJson("/api/pertanyaan/{$pertanyaan->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('pertanyaans', ['id' => $pertanyaan->id]);
    }

    public function test_validasi_pertanyaan_store_gagal(): void
    {
        Sanctum::actingAs($this->admin);

        $data = [
            'judul' => '',  // ← Wajib diisi
            'isi' => '',    // ← Wajib diisi
            'status' => 'invalid',  // ← Harus aktif atau tidak_aktif
        ];

        $response = $this->postJson('/api/pertanyaan', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['judul', 'isi', 'status']);
    }

    public function test_pertanyaan_tidak_bisa_diakses_tanpa_token(): void
    {
        $response = $this->getJson('/api/pertanyaan');

        $response->assertStatus(401);
    }
}
