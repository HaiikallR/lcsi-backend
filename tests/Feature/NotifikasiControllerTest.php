<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Notifikasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class NotifikasiControllerTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
    }

    public function test_admin_dapat_melihat_list_notifikasi(): void
    {
        Sanctum::actingAs($this->admin);
        Notifikasi::factory(5)->create(['id_admin' => $this->admin->id]);

        $response = $this->getJson('/api/notifikasi');

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['*' => ['id', 'judul', 'pesan', 'tipe', 'status', 'dibuat_pada']]]);
    }

    public function test_admin_dapat_membuat_notifikasi_baru(): void
    {
        Sanctum::actingAs($this->admin);

        $data = [
            'id_admin' => $this->admin->id,
            'judul' => 'Notifikasi Penting',
            'pesan' => 'Ini adalah pesan notifikasi yang penting untuk diperhatikan.',
            'tipe' => 'warning',
            'status' => 'belum_dibaca',
        ];

        $response = $this->postJson('/api/notifikasi', $data);

        $response->assertStatus(201)
            ->assertJsonPath('id', 1)
            ->assertJsonPath('judul', $data['judul'])
            ->assertJsonPath('tipe', 'warning');

        $this->assertDatabaseHas('notifikasis', ['judul' => $data['judul']]);
    }

    public function test_admin_dapat_melihat_detail_notifikasi(): void
    {
        Sanctum::actingAs($this->admin);
        $notifikasi = Notifikasi::factory()->create(['id_admin' => $this->admin->id]);

        $response = $this->getJson("/api/notifikasi/{$notifikasi->id}");

        $response->assertStatus(200)
            ->assertJsonPath('id', $notifikasi->id)
            ->assertJsonPath('judul', $notifikasi->judul);
    }

    public function test_admin_dapat_mengupdate_notifikasi(): void
    {
        Sanctum::actingAs($this->admin);
        $notifikasi = Notifikasi::factory()->create(['id_admin' => $this->admin->id]);

        $data = [
            'id_admin' => $this->admin->id,
            'judul' => 'Notifikasi yang diupdate',
            'pesan' => 'Pesan yang sudah diupdate',
            'tipe' => 'info',
            'status' => 'dibaca',
        ];

        $response = $this->putJson("/api/notifikasi/{$notifikasi->id}", $data);

        $response->assertStatus(200)
            ->assertJsonPath('judul', $data['judul'])
            ->assertJsonPath('status', 'dibaca');

        $this->assertDatabaseHas('notifikasis', ['judul' => $data['judul']]);
    }

    public function test_admin_dapat_menghapus_notifikasi(): void
    {
        Sanctum::actingAs($this->admin);
        $notifikasi = Notifikasi::factory()->create(['id_admin' => $this->admin->id]);

        $response = $this->deleteJson("/api/notifikasi/{$notifikasi->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('notifikasis', ['id' => $notifikasi->id]);
    }

    public function test_validasi_notifikasi_store_gagal(): void
    {
        Sanctum::actingAs($this->admin);

        $data = [
            'id_admin' => '',  // ← Wajib diisi
            'judul' => '',  // ← Wajib diisi
            'pesan' => '',  // ← Wajib diisi
            'tipe' => 'invalid',  // ← Harus info, warning, error, success
        ];

        $response = $this->postJson('/api/notifikasi', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['id_admin', 'judul', 'pesan', 'tipe']);
    }

    public function test_notifikasi_tidak_bisa_diakses_tanpa_token(): void
    {
        $response = $this->getJson('/api/notifikasi');

        $response->assertStatus(401);
    }
}
