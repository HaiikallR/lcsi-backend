<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TagihanController extends Controller
{
    /**
     * Menampilkan daftar tagihan dengan filter status, bulan, dan tahun.
     */
    public function index(Request $request)
    {
        $query = Tagihan::with('pelanggan');

        if ($request->status && $request->status != 'Semua') {
            $query->where('status', $request->status);
        }

        if ($request->bulan) {
            $query->where('bulan', $request->bulan);
        }

        if ($request->tahun) {
            $query->where('tahun', $request->tahun);
        }

        if ($request->search) {
            $query->whereHas('pelanggan', function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%");
            });
        }

        return response()->json([
            'success' => true,
            'data' => $query->orderBy('created_at', 'desc')->get()
        ]);
    }

    /**
     * Generate Tagihan Massal untuk semua pelanggan.
     */
    public function generateMassal(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required',
            'nominal' => 'required|numeric'
        ]);

        return DB::transaction(function () use ($request) {
            $pelanggans = Pelanggan::all();
            $count = 0;
            $skipped = 0;

            foreach ($pelanggans as $user) {
                // Logika Cek Duplikat: Hindari tagihan ganda di periode yang sama
                $exists = Tagihan::where('id_user', $user->id)
                    ->where('bulan', $request->bulan)
                    ->where('tahun', $request->tahun)
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                Tagihan::create([
                    'id_user' => $user->id,
                    'nama_pelanggan' => $user->nama, // Snapshot nama saat tagihan dibuat
                    'bulan' => $request->bulan,
                    'tahun' => $request->tahun,
                    'jumlah' => $request->nominal,
                    'status' => 'Belum Dibayar',
                    'paket' => $user->paket ?? "",
                ]);
                $count++;
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil: $count tagihan. Dilewati: $skipped (sudah ada)."
            ]);
        });
    }

    /**
     * Generate Tagihan Tunggal / Khusus Corporate.
     */
    public function generateTunggal(Request $request)
    {
        $request->validate([
            'id_user' => 'required',
            'bulan' => 'required',
            'tahun' => 'required',
            'nominal' => 'required|numeric'
        ]);

        $exists = Tagihan::where('id_user', $request->id_user)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => "Pelanggan ini sudah memiliki tagihan untuk periode tersebut."
            ], 422);
        }

        $user = Pelanggan::findOrFail($request->id_user);

        $tagihan = Tagihan::create([
            'id_user' => $user->id,
            'nama_pelanggan' => $user->nama,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'jumlah' => $request->nominal,
            'status' => 'Belum Dibayar',
            'paket' => $user->paket ?? "",
            'tipe' => 'custom', // Penanda tagihan khusus
        ]);

        return response()->json([
            'success' => true,
            'message' => "Tagihan khusus berhasil dibuat.",
            'data' => $tagihan
        ]);
    }

    /**
     * Update Status Tagihan (Belum Dibayar, Menunggu Admin, Lunas).
     */
    public function updateStatus(Request $request, $id)
    {
        $tagihan = Tagihan::findOrFail($id);
        $tagihan->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => "Status berhasil diubah menjadi {$request->status}"
        ]);
    }

    /**
     * Menghapus tagihan.
     */
    public function destroy($id)
    {
        Tagihan::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Tagihan dihapus.']);
    }
}
