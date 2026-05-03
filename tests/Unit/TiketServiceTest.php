<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Pelanggan;
use App\Models\Pengeluaran;
use App\Models\Teknisi;
use App\Models\Tiket;
use App\Services\TiketService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TiketServiceTest extends TestCase
{
    use RefreshDatabase;

    private TiketService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TiketService();
    }

    // ──────────────────────────────────────────────────────────
    // normalizeData()
    // ──────────────────────────────────────────────────────────

    public function test_normalize_data_pasang_baru_mengosongkan_id_pelanggan(): void
    {
        $data = [
            'jenis_pekerjaan' => 'pasang baru',
            'id_pelanggan' => 5,
            'calon_pelanggan_nama' => 'Budi',
            'calon_pelanggan_no_hp' => '081234',
            'calon_pelanggan_alamat' => 'Jl. A',
        ];

        $result = $this->service->normalizeData($data);

        $this->assertNull($result['id_pelanggan']);
        $this->assertSame('Budi', $result['calon_pelanggan_nama']);
    }

    public function test_normalize_data_non_pasang_baru_mengosongkan_calon_pelanggan(): void
    {
        $data = [
            'jenis_pekerjaan' => 'maintenance',
            'id_pelanggan' => 5,
            'calon_pelanggan_nama' => 'Budi',
            'calon_pelanggan_no_hp' => '081234',
            'calon_pelanggan_alamat' => 'Jl. A',
        ];

        $result = $this->service->normalizeData($data);

        $this->assertSame(5, $result['id_pelanggan']);
        $this->assertNull($result['calon_pelanggan_nama']);
        $this->assertNull($result['calon_pelanggan_no_hp']);
        $this->assertNull($result['calon_pelanggan_alamat']);
    }

    // ──────────────────────────────────────────────────────────
    // selesai()
    // ──────────────────────────────────────────────────────────

    public function test_selesai_mengubah_status_tiket_dan_membuat_pengeluaran(): void
    {
        $pelanggan = Pelanggan::factory()->create();
        $teknisi = Teknisi::factory()->create();

        $tiket = Tiket::factory()->create([
            'id_pelanggan' => $pelanggan->id,
            'id_teknisi' => $teknisi->id,
            'status' => 'in progress',
            'ongkos_teknisi' => 300000,
        ]);

        $pengeluaran = $this->service->selesai($tiket);

        $tiket->refresh();
        $this->assertSame('selesai', $tiket->status);
        $this->assertNotNull($tiket->tanggal_selesai);
        $this->assertInstanceOf(Pengeluaran::class, $pengeluaran);
        $this->assertSame($tiket->id, $pengeluaran->id_tiket);
        $this->assertSame(300000, $pengeluaran->jumlah);
    }

    public function test_selesai_tidak_duplikasi_pengeluaran_jika_dipanggil_dua_kali(): void
    {
        $pelanggan = Pelanggan::factory()->create();
        $teknisi = Teknisi::factory()->create();

        $tiket = Tiket::factory()->create([
            'id_pelanggan' => $pelanggan->id,
            'id_teknisi' => $teknisi->id,
            'status' => 'in progress',
            'ongkos_teknisi' => 150000,
        ]);

        $this->service->selesai($tiket);
        $this->service->selesai($tiket);

        $this->assertCount(1, Pengeluaran::where('id_tiket', $tiket->id)->get());
    }
}
