<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemberitahuan;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PemberitahuanController extends Controller
{
    /**
     * Menampilkan riwayat Pemberitahuan yang telah dikirim.
     * Diurutkan dari yang terbaru (descending).
     */
    public function index()
    {
        $notifications = Pemberitahuan::orderBy('tanggal_sent', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    /**
     * Mengirim Pemberitahuan broadcast dan menyimpan riwayatnya.
     */
    public function sendBroadcast(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul'     => 'required|string',
            'isi_pesan' => 'required|string',
            'kategori'  => 'required|in:Info,Gangguan,Promo',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // --- LOGIKA BROADCAST EXTERNAL ---
            // Di sini kamu bisa memanggil API WhatsApp Gateway atau FCM.
            // Contoh: $this->sendToWhatsApp($request->judul, $request->isi_pesan);

            // --- SIMPAN KE DATABASE (RIWAYAT) ---
            $notif = Pemberitahuan::create([
                'judul'        => $request->judul,
                'isi_pesan'    => $request->isi_pesan,
                'kategori'     => $request->kategori,
                'tanggal_sent' => Carbon::now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => "Pemberitahuan Berhasil di-Broadcast ke Semua Pelanggan!",
                'data'    => $notif
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Terjadi kesalahan: " . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghapus riwayat Pemberitahuan.
     */
    public function destroy($id)
    {
        $notif = Pemberitahuan::findOrFail($id);
        $notif->delete();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat Pemberitahuan berhasil dihapus'
        ]);
    }
}
