<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pengeluaran;
use App\Models\Tiket;
use App\Models\Teknisi;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function index()
    {
        $pengeluarans = Pengeluaran::with(['tiket:id,jenis_pekerjaan', 'teknisi:id,nama'])
            ->orderByDesc('id')->paginate(10);
        return view('pengeluaran.index', compact('pengeluarans'));
    }

    public function create()
    {
        $tikets   = Tiket::with('pelanggan:id,nama')->whereDoesntHave('pengeluaran')->orderByDesc('id')->get();
        $teknisis = Teknisi::orderByDesc('nama')->get();
        return view('pengeluaran.create', compact('tikets', 'teknisis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'    => ['required', 'string'],
            'jumlah'   => ['required', 'integer', 'min:0'],
            'kategori' => ['required', 'string'],
            'bulan'    => ['required', 'string'],
            'tahun'    => ['required', 'string'],
        ]);

        // 1. Simpan data pengeluaran
        Pengeluaran::create($request->only('id_tiket', 'id_teknisi', 'judul', 'jumlah', 'kategori', 'bulan', 'tahun'));

        // 2. Logika Update Status Tiket secara Otomatis
        // Jika ada id_tiket yang dipilih, ubah status tiket tersebut menjadi 'Selesai'
        if ($request->filled('id_tiket')) {
            \App\Models\Tiket::query()->where('id', $request->id_tiket)->update([
                'status' => 'Selesai',
                'tanggal_selesai' => now() // Tambahan: set tanggal selesai saat itu juga
            ]);
        }

        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran berhasil ditambahkan dan status tiket telah diperbarui.');
    }

    public function edit(Pengeluaran $pengeluaran)
    {
        return view('pengeluaran.edit', compact('pengeluaran'));
    }

    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $request->validate([
            'judul'    => ['required', 'string'],
            'jumlah'   => ['required', 'integer', 'min:0'],
            'kategori' => ['required', 'string'],
            'bulan'    => ['required', 'string'],
            'tahun'    => ['required', 'string'],
        ]);

        $pengeluaran->update($request->only('judul', 'jumlah', 'kategori', 'bulan', 'tahun'));

        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran berhasil diperbarui.');
    }
    public function getTiketTerbuka()
    {
        try {
            // Kita ambil tiket yang statusnya 'Proses' atau 'Pending'
            // dan kita sertakan data pelanggan agar tampil di dropdown
            $tikets = Tiket::with('pelanggan')
                ->where('status', '!=', 'Selesai')
                ->get();

            return response()->json($tikets);
        } catch (\Exception $e) {
            // Jika ada yang salah, kirim pesan error agar tidak 500
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete($pengeluaran->id);
        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran berhasil dihapus.');
    }
}
