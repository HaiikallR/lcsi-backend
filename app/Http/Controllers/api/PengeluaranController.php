<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PengeluaranController extends Controller
{
    /**
     * Menampilkan daftar pengeluaran berdasarkan filter bulan dan tahun.
     */
    public function index(Request $request)
    {
        // Default ke bulan dan tahun sekarang jika tidak ada input
        $bulan = $request->get('bulan', Carbon::now()->translatedFormat('F'));
        $tahun = $request->get('tahun', Carbon::now()->year);

        $expenses = Pengeluaran::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->orderBy('tanggal', 'desc')
            ->get();

        // Hitung total untuk ringkasan laporan
        $totalPengeluaran = $expenses->sum('jumlah');

        return response()->json([
            'success' => true,
            'periode' => "$bulan $tahun",
            'total_pengeluaran' => $totalPengeluaran,
            'data' => $expenses
        ]);
    }

    /**
     * Menyimpan catatan pengeluaran baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul'    => 'required|string',
            'kategori' => 'required|in:Operasional,Gaji,Sewa,Pembelian Alat,Lainnya',
            'jumlah'   => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Otomatis tentukan bulan, tahun, dan tanggal
        $now = Carbon::now();

        $expense = Pengeluaran::create([
            'judul'    => $request->judul,
            'kategori' => $request->kategori,
            'jumlah'   => $request->jumlah,
            'tanggal'  => $now,
            'bulan'    => $now->translatedFormat('F'), // Contoh: Januari
            'tahun'    => $now->year,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengeluaran berhasil dicatat',
            'data'    => $expense
        ], 201);
    }

    /**
     * Menghapus catatan pengeluaran.
     */
    public function destroy($id)
    {
        $expense = Pengeluaran::findOrFail($id);
        $expense->delete();

        return response()->json([
            'success' => true,
            'message' => 'Catatan pengeluaran berhasil dihapus'
        ]);
    }

    /**
     * Endpoint khusus untuk data laporan PDF.
     */
    public function report(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required'
        ]);

        $data = Pengeluaran::where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->get();

        return response()->json([
            'meta' => [
                'organisasi' => 'LCSI - Laporan Pengeluaran',
                'periode' => $request->bulan . ' ' . $request->tahun,
                'total' => $data->sum('jumlah')
            ],
            'items' => $data
        ]);
    }
}
