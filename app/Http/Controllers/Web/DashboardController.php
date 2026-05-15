<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Tiket;
use App\Models\Tagihan;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\PermintaanUpgrade;

class DashboardController extends Controller
{
    public function index()
    {

        $statistik = [
            'total_pelanggan'     => Pelanggan::query()->count(),
            'tiket_aktif'         => Tiket::query()->where('status', 'in progress')->count(),
            'tagihan_belum_bayar' => Tagihan::query()->where('status', 'belum bayar')->count(),
            'total_pemasukan'     => Pemasukan::query()->sum('jumlah_bayar'),
            'total_pengeluaran'   => Pengeluaran::query()->sum('jumlah'),
        ];


        $tiket_terbaru = Tiket::with(['pelanggan:id,nama', 'teknisi:id,nama'])
            ->latest()->take(5)->get();

        $tagihan_terbaru = Tagihan::with('pelanggan:id,nama')
            ->latest()->take(5)->get();

        $upgrade_terbaru = PermintaanUpgrade::with('pelanggan:id,nama')
            ->where('status', 'menunggu')->latest()->take(5)->get();

        return view('dashboard.index', compact(
            'statistik',
            'tiket_terbaru',
            'tagihan_terbaru',
            'upgrade_terbaru'
        ));
    }
}
