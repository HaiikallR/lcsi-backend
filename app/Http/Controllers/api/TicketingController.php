<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tiket;
use App\Models\Pelanggan;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\DB;

class TicketingController extends Controller
{
    public function index()
    {
        $tickets = Tiket::with(['pelanggan', 'teknisi'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $tickets
        ]);
    }

    /**
     * Membuat Tiket baru (Pasang Baru / Perbaikan).
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_pekerjaan' => 'required',
            'id_teknisi' => 'required',
            'ongkos_teknisi' => 'required|numeric',
        ]);

        return DB::transaction(function () use ($request) {
            // Logika Pasang Baru vs Pelanggan Terdaftar
            if ($request->jenis_pekerjaan == 'Pasang Baru') {
                $namaPelanggan = $request->nama_pelanggan;
                $alamat = $request->alamat_pelanggan;
                $phone = $request->phone_pelanggan;
            } else {
                $pelanggan = Pelanggan::findOrFail($request->id_pelanggan);
                $namaPelanggan = $pelanggan->nama;
                $alamat = $pelanggan->alamat;
                $phone = $pelanggan->no_telp;
            }

            $tiket = Tiket::create([
                'id_pelanggan' => $request->id_pelanggan, // Null jika Pasang Baru
                'nama_pelanggan_manual' => $namaPelanggan,
                'alamat_pelanggan_manual' => $alamat,
                'phone_pelanggan_manual' => $phone,
                'id_teknisi' => $request->id_teknisi,
                'jenis_pekerjaan' => $request->jenis_pekerjaan,
                'ongkos_teknisi' => $request->ongkos_teknisi,
                'status' => 'In Progress',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ticket berhasil dibuat',
                'data' => $tiket
            ]);
        });
    }

    /**
     * Menandai Tiket Selesai & Otomatis Mencatat Pengeluaran.
     */
    public function markAsCompleted($id)
    {
        return DB::transaction(function () use ($id) {
            $tiket = Tiket::findOrFail($id);

            if ($tiket->status == 'Completed') {
                return response()->json(['message' => 'Tiket sudah selesai'], 400);
            }

            // 1. Update Status Tiket
            $tiket->update([
                'status' => 'Completed',
                'completed_at' => now()
            ]);

            // 2. Catat ke Tabel Pengeluaran (Expenses)
            Pengeluaran::create([
                'judul' => $tiket->jenis_pekerjaan . " - " . ($tiket->pelanggan->nama ?? $tiket->nama_pelanggan_manual),
                'nama_teknisi' => $tiket->teknisi->nama_teknisi ?? 'Admin',
                'jumlah' => $tiket->ongkos_teknisi,
                'kategori' => $tiket->jenis_pekerjaan,
                'id_tiket' => $tiket->id_tiket,
                'bulan' => now()->format('F'),
                'tahun' => now()->format('Y'),
                'tanggal' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tiket selesai dan pengeluaran telah dicatat.'
            ]);
        });
    }

    /**
     * Mengambil Detail WhatsApp Teknisi.
     */
    public function getWhatsAppDetail($id)
    {
        $tiket = Tiket::with(['teknisi', 'pelanggan'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'phone_teknisi' => $tiket->teknisi->no_hp,
                'pesan' => "Detail Tiket Pekerjaan:\n" .
                    "Jenis: " . $tiket->jenis_pekerjaan . "\n" .
                    "Pelanggan: " . ($tiket->pelanggan->nama ?? $tiket->nama_pelanggan_manual)
            ]
        ]);
    }
}
