<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Pelanggan;
use App\Models\Teknisi;
use App\Models\Tiket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TiketControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_tiket_pasang_baru_berhasil(): void
    {
        Sanctum::actingAs(Admin::factory()->create());
        $teknisi = Teknisi::factory()->create();

        $response = $this->postJson('/api/tiket', [
            'jenis_pekerjaan' => 'pasang baru',
            'calon_pelanggan_nama' => 'Calon A',
            'calon_pelanggan_no_hp' => '08123456789',
            'calon_pelanggan_alamat' => 'Jl. Mawar',
            'id_teknisi' => $teknisi->id,
            'ongkos_teknisi' => 150000,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.status', 'in progress')
            ->assertJsonPath('data.calon_pelanggan_nama', 'Calon A');
    }

    public function test_store_tiket_non_pasang_baru_wajib_pelanggan(): void
    {
        Sanctum::actingAs(Admin::factory()->create());
        $teknisi = Teknisi::factory()->create();

        $response = $this->postJson('/api/tiket', [
            'jenis_pekerjaan' => 'maintenance',
            'id_teknisi' => $teknisi->id,
            'ongkos_teknisi' => 100000,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['id_pelanggan']);
    }

    public function test_kirim_whatsapp_menghasilkan_payload_url(): void
    {
        Sanctum::actingAs(Admin::factory()->create());
        $tiket = Tiket::factory()->create();

        $response = $this->postJson('/api/tiket/'.$tiket->id.'/kirim-whatsapp');

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['pesan', 'url_whatsapp']]);
    }

    public function test_selesai_tiket_membuat_pengeluaran_otomatis(): void
    {
        Sanctum::actingAs(Admin::factory()->create());
        $tiket = Tiket::factory()->create([
            'status' => 'in progress',
            'tanggal_selesai' => null,
            'ongkos_teknisi' => 200000,
        ]);

        $response = $this->postJson('/api/tiket/'.$tiket->id.'/selesai');

        $response->assertStatus(200);

        $this->assertDatabaseHas('tikets', [
            'id' => $tiket->id,
            'status' => 'selesai',
        ]);

        $this->assertDatabaseHas('pengeluarans', [
            'id_tiket' => $tiket->id,
            'id_teknisi' => $tiket->id_teknisi,
            'jumlah' => 200000,
        ]);
    }
}