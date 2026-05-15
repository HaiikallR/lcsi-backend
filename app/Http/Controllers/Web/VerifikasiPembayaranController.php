<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\VerifikasiPembayaran;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use App\Models\Pemasukan;
use Illuminate\Support\Facades\Storage;

class VerifikasiPembayaranController extends Controller
{
    /**
     * Daftar semua verifikasi pembayaran
     */
    public function index()
    {

        $verifikasis = Pemasukan::with([
            'pelanggan:id,nama',
        ])
            ->orderByDesc('id')
            ->paginate(10);

        // Hitung statistik untuk header
        $statistik = [
            'menunggu'  => Pemasukan::query()->where('status', 'menunggu')->count(),
            'lunas' => Pemasukan::query()->where('status', 'lunas')->count(),
            'ditolak'   => Pemasukan::query()->where('status', 'ditolak')->count(),
        ];

        return view('verifikasi-pembayaran.index', compact('verifikasis', 'statistik'));
    }

    /**
     * Detail verifikasi + lihat bukti transfer
     */
    public function show(Pemasukan $verifikasiPembayaran)
    {
        $verifikasiPembayaran->load([
            'pelanggan',
            'tagihan'
        ]);

        return view('verifikasi-pembayaran.show', compact('verifikasiPembayaran'));
    }

    /**
     * Setujui pembayaran → update status tagihan jadi sudah bayar
     */
    public function setujui(Pemasukan $verifikasiPembayaran)
    {
        // Update status verifikasi
        $verifikasiPembayaran->update([
            'status'    => 'Lunas',
            'diverifikasi_pada'  => now(),
        ]);

        // Update status tagihan terkait
        $verifikasiPembayaran->tagihan?->update([
            'status'       => 'sudah bayar',
            'tanggal_bayar' => now(),
        ]);
        return redirect()->route('verifikasi-pembayaran.index')
            ->with('success', 'Pembayaran ' . $verifikasiPembayaran->pelanggan->nama . ' berhasil disetujui.');
    }

    /**
     * Tolak pembayaran
     */
    public function tolak(Request $request, Pemasukan $verifikasiPembayaran)
    {
        $request->validate([
            'keterangan' => ['required', 'string', 'min:5'],
        ]);

        $verifikasiPembayaran->update([
            'status'      => 'ditolak',
            'keterangan'  => $request->keterangan,
            'diverifikasi_pada' => now(),
        ]);


        // Kembalikan status tagihan ke belum bayar
        $verifikasiPembayaran->tagihan->update([
            'status' => 'belum bayar',
        ]);

        return redirect()->route('verifikasi-pembayaran.index')
            ->with('success', 'Pembayaran ' . $verifikasiPembayaran->pelanggan->nama . ' ditolak.');
    }

    /**
     * Hapus data verifikasi
     */
    public function destroy(Pemasukan $verifikasiPembayaran)
    {
        // Hapus file bukti transfer
        if ($verifikasiPembayaran->bukti_transfer) {
            Storage::disk('public')->delete($verifikasiPembayaran->bukti_transfer);
        }

        $verifikasiPembayaran->delete($verifikasiPembayaran->id);

        return redirect()->route('verifikasi-pembayaran.index')
            ->with('success', 'Data verifikasi berhasil dihapus.');
    }
}
