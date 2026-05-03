<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Pelanggan;
use App\Models\Teknisi;
use App\Models\Tiket;
use App\Services\WhatsappTicketMessageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhatsappTicketMessageServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_build_payload_untuk_pasang_baru(): void
    {
        $teknisi = Teknisi::factory()->create(['no_hp' => '+62 812-1234-5678']);

        $tiket = Tiket::factory()->pasangBaru()->create([
            'id_teknisi' => $teknisi->id,
            'status' => 'in progress',
            'ongkos_teknisi' => 200000,
            'calon_pelanggan_nama' => 'Calon A',
            'calon_pelanggan_no_hp' => '081234567890',
            'calon_pelanggan_alamat' => 'Jl. Mawar',
        ])->load('teknisi');

        $payload = app(WhatsappTicketMessageService::class)->buildPayload($tiket);

        $this->assertArrayHasKey('pesan', $payload);
        $this->assertArrayHasKey('url_whatsapp', $payload);
        $this->assertStringContainsString('Calon pelanggan: Calon A / 081234567890 / Jl. Mawar', $payload['pesan']);
        $this->assertStringStartsWith('https://wa.me/6281212345678?text=', $payload['url_whatsapp']);
    }

    public function test_build_payload_untuk_pelanggan_existing(): void
    {
        $pelanggan = Pelanggan::factory()->create(['nama' => 'Pelanggan X']);
        $teknisi = Teknisi::factory()->create(['no_hp' => '081300011122']);

        $tiket = Tiket::factory()->create([
            'jenis_pekerjaan' => 'maintenance',
            'id_pelanggan' => $pelanggan->id,
            'id_teknisi' => $teknisi->id,
            'status' => 'in progress',
            'ongkos_teknisi' => 150000,
            'calon_pelanggan_nama' => null,
            'calon_pelanggan_no_hp' => null,
            'calon_pelanggan_alamat' => null,
        ])->load(['pelanggan', 'teknisi']);

        $payload = app(WhatsappTicketMessageService::class)->buildPayload($tiket);

        $this->assertStringContainsString('Pelanggan: Pelanggan X', $payload['pesan']);
        $this->assertStringContainsString('Jenis: maintenance', $payload['pesan']);
        $this->assertStringStartsWith('https://wa.me/081300011122?text=', $payload['url_whatsapp']);
    }
}