<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Pemasukan;
use App\Models\Tiket;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $totalIncome = Pemasukan::where('status', 'Lunas')->sum('jumlah_bayar');

            $totalCustomers = Pelanggan::count();

            $pendingPayments = Pemasukan::where('status', 'Pending')->count();

            return response()->json([
                'success' => true,
                'message' => 'Statistik Dashboard berhasil dimuat',
                'data' => [
                    'totalIncome' => (float) $totalIncome,
                    'totalCustomers' => (int) $totalCustomers,
                    'pendingPayments' => (int) $pendingPayments,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data: ' . $e->getMessage()
            ], 500);
        }
    }
}
