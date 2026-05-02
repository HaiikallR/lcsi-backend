<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Pemasukan;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\DB;

class VerificationController extends Controller
{
    public function index()
    {
        $pendingVerifications = Tagihan::with('pelanggan')
            ->where('status', 'Menunggu Admin')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pendingVerifications
        ]);
    }


    public function verify(Request $request, $id)
    {
        return DB::transaction(function () use ($id) {
            $tagihan = Tagihan::findOrFail($id);

            $tagihan->update([
                'status' => 'Lunas',
                'tgl_verifikasi' => now(),
            ]);

            $pelanggan = Pelanggan::find($tagihan->id_user);

            Pemasukan::create([
                'id_user' => $tagihan->id_user,
                'nama_pelanggan' => $pelanggan->nama ?? 'Pelanggan',
                'jumlah_bayar' => $tagihan->jumlah_tagihan,
                'bulan_tagihan' => $tagihan->bulan,
                'tahun_tagihan' => $tagihan->tahun,
                'jenis_pemasukan' => 'Pembayaran Internet',
                'metode_bayar' => 'Transfer (Verified)',
                'bukti_bayar' => $tagihan->bukti_bayar,
                'status' => 'Lunas',
                'tanggal_bayar' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil! Tagihan Lunas & Dicatat di Pemasukan.'
            ]);
        });
    }

    /**
     
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'required|string'
        ]);

        $tagihan = Tagihan::findOrFail($id);

        $tagihan->update([
            'status' => 'Belum Dibayar',
            'catatan_admin' => $request->alasan, // Menyimpan alasan penolakan[cite: 3]
            'waktu_verifikasi' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran ditolak. Status kembali menjadi Belum Dibayar.'
        ]);
    }
}
