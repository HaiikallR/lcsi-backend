<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Pemasukan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PemasukkanController extends Controller
{
    /**
     * Menampilkan data pemasukan dengan filter bulan, tahun, dan jenis.
     */
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now()->translatedFormat('F'));
        $tahun = $request->get('tahun', Carbon::now()->year);

        $query = Pemasukan::where('bulan_tagihan', $bulan)
            ->where('tahun_tagihan', $tahun);

        if ($request->filter_status && $request->filter_status != 'Semua') {
            $query->where('jenis_pemasukan', $request->filter_status);
        }

        $incomes = $query->orderBy('tanggal_bayar', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $incomes
        ]);
    }

    /**
     * Menyimpan pemasukan baru (Internet atau Pemasukan Lain).
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_pemasukan' => 'required|in:Pembayaran Internet,Pemasukan Lain',
            'jumlah_bayar'    => 'required|numeric|min:1',
        ]);

        return DB::transaction(function () use ($request) {
            $data = [
                'jenis_pemasukan' => $request->jenis_pemasukan,
                'jumlah_bayar'    => $request->jumlah_bayar,
                'tanggal_bayar'   => Carbon::now(),
                'status'          => 'Lunas',
                'metode_bayar'    => 'Tunai/Manual (Admin)',
                'tahun_tagihan'   => $request->tahun_tagihan ?? Carbon::now()->year,
            ];

            // Logika Spesifik Pembayaran Internet
            if ($request->jenis_pemasukan == 'Pembayaran Internet') {
                $tagihan = Tagihan::findOrFail($request->id_tagihan);

                $data['id_user']        = $tagihan->id_user;
                $data['nama_pelanggan'] = $tagihan->nama_pelanggan;
                $data['bulan_tagihan']  = $tagihan->bulan;

                // Update status tagihan asal menjadi Lunas
                $tagihan->update(['status' => 'Lunas']);
            } else {
                // Logika Pemasukan Lain
                $data['keterangan']    = $request->keterangan;
                $data['bulan_tagihan'] = $request->bulan_tagihan ?? Carbon::now()->translatedFormat('F');
            }

            $pemasukan = Pemasukan::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Pemasukan telah dicatat ke Kas',
                'data'    => $pemasukan
            ], 201);
        });
    }

    /**
     * Menghapus catatan pemasukan.
     */
    public function destroy($id)
    {
        $pemasukan = Pemasukan::findOrFail($id);
        $pemasukan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data pemasukan berhasil dihapus'
        ]);
    }
}
