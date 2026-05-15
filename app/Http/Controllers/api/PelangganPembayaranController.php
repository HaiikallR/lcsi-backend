<?php


declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pemasukan;
use App\Models\Tagihan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PelangganPembayaranController extends Controller
{
    /**
     * GET /api/pelanggan/tagihan
     * Ambil semua tagihan milik pelanggan yang sedang login
     */
    public function tagihanSaya(Request $request): JsonResponse
    {
        $pelanggan = $request->user();

        // ✅ Pastikan ada dd() untuk cek dulu
        // dd($pelanggan);

        // ✅ Query ringan — tidak pakai with(), pilih kolom spesifik
        $tagihans = Tagihan::query()
            ->where('id_pelanggan', $pelanggan->id)
            ->orderByDesc('id')
            ->get(['id', 'bulan', 'tahun', 'jumlah', 'status', 'catatan', 'tanggal_bayar']);

        return response()->json([
            'data' => $tagihans,
        ]);
    }

    /**
     * POST /api/pelanggan/bayar
     * Pelanggan upload bukti bayar
     * → Simpan ke tabel pemasukans
     * → Update status tagihan → 'menunggu'
     */
    public function bayar(Request $request): JsonResponse
    {
        $request->validate([
            'id_tagihan'  => ['required', 'exists:tagihans,id'],
            'metode_bayar' => ['required', 'string', 'max:50'],
            'bukti_bayar' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'keterangan'  => ['nullable', 'string'],
        ]);

        $pelanggan = $request->user();

        // Cek tagihan milik pelanggan ini
        $tagihan = Tagihan::query()
            ->where('id', $request->id_tagihan)
            ->where('id_pelanggan', $pelanggan->id)
            ->firstOrFail();

        // Cek tagihan belum lunas
        if ($tagihan->status === 'sudah bayar') {
            return response()->json([
                'message' => 'Tagihan ini sudah lunas.',
            ], 422);
        }

        // Upload file bukti bayar
        $path = $request->file('bukti_bayar')
            ->store('bukti_bayar', 'public');

        DB::transaction(function () use ($request, $pelanggan, $tagihan, $path) {

            // Simpan ke tabel pemasukans
            Pemasukan::create([
                'id_pelanggan'  => $pelanggan->id,
                'id_tagihan'    => $tagihan->id,
                'jenis_pemasukan' => 'Tagihan Bulanan',
                'jumlah_bayar'  => $tagihan->jumlah,
                'metode_bayar'  => $request->metode_bayar,
                'bukti_bayar'   => $path,
                'keterangan'    => $request->keterangan,
                'status'        => 'menunggu',
                'bulan_tagihan' => $tagihan->bulan,
                'tahun_tagihan' => $tagihan->tahun,
                'tanggal_bayar' => now(),
            ]);

            // Update status tagihan → menunggu verifikasi admin
            $tagihan->update(['status' => 'menunggu']);
        });

        return response()->json([
            'message' => 'Bukti pembayaran berhasil dikirim. Menunggu verifikasi admin.',
        ], 201);
    }

    /**
     * GET /api/pelanggan/tagihan/{id}/pemasukan
     * Cek apakah tagihan ini sudah ada pengajuan pembayarannya
     * dan ambil catatan jika ditolak
     */
    public function cekPembayaran(Request $request, int $id): JsonResponse
    {
        $pelanggan = $request->user();

        $tagihan = Tagihan::query()
            ->where('id', $id)
            ->where('id_pelanggan', $pelanggan->id)
            ->firstOrFail();

        // Cari pemasukan terkait tagihan ini
        $pemasukan = Pemasukan::query()
            ->where('id_tagihan', $tagihan->id)
            ->latest()
            ->first();

        return response()->json([
            'tagihan'   => $tagihan,
            'pemasukan' => $pemasukan ? [
                'id'          => $pemasukan->id,
                'status'      => $pemasukan->status,
                'bukti_bayar' => $pemasukan->bukti_bayar
                    ? Storage::url($pemasukan->bukti_bayar)
                    : null,
                'keterangan'  => $pemasukan->keterangan,
                'created_at'  => $pemasukan->created_at,
            ] : null,
        ]);
    }
}
