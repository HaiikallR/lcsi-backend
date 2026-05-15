<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pemasukan;
use App\Models\Pelanggan;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PemasukanController extends Controller
{
    public function index()
    {
        $pemasukans = Pemasukan::with('pelanggan:id,nama')
            ->orderByDesc('id')
            ->paginate(10);
        return view('pemasukan.index', compact('pemasukans'));
    }

    public function create()
    {
        $pelanggans = Pelanggan::query()->where('status', 'aktif')->orderBy('nama')->get();

        return view('pemasukan.create', compact('pelanggans'));
    }

    /**
     * AJAX — Ambil tagihan belum lunas milik pelanggan tertentu
     * GET /pemasukan/tagihan-pelanggan?id_pelanggan=1
     */
    public function getTagihanPelanggan(Request $request)

    {
        $tagihans = Tagihan::query()->where('id_pelanggan', $request->id_pelanggan)
            ->whereIn('status', ['belum bayar', 'menunggu'])
            ->orderByRaw("FIELD(bulan,
                'Januari','Februari','Maret','April','Mei','Juni',
                'Juli','Agustus','September','Oktober','November','Desember'
            )")
            ->orderBy('tahun')
            ->get(['id', 'bulan', 'tahun', 'jumlah', 'status']);

        return response()->json($tagihans);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_pelanggan'    => ['required', 'exists:pelanggans,id'],
            'id_tagihan'      => ['nullable', 'exists:tagihans,id'],
            'jenis_pemasukan' => ['required', 'string'],
            'jumlah_bayar'    => ['required', 'integer', 'min:0'],
            'metode_bayar'    => ['required', 'string'],
            'status'          => ['required', 'in:menunggu,lunas'],
            'bulan_tagihan'   => ['nullable', 'string'],
            'tahun_tagihan'   => ['nullable', 'string'],
            'tanggal_bayar'   => ['nullable', 'date'],
            'keterangan'      => ['nullable', 'string'],
            'bukti_bayar'     => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        if ($request->hasFile('bukti_bayar')) {
            $data['bukti_bayar'] = $request->file('bukti_bayar')
                ->store('bukti_bayar', 'public');
        }

        // Pakai DB transaction agar konsisten
        DB::transaction(function () use ($data, $request) {

            // Simpan pemasukan
            Pemasukan::create($data);

            // Jika ada tagihan terkait DAN status lunas
            // → update status tagihan otomatis
            if (!empty($data['id_tagihan']) && $data['status'] === 'lunas') {
                Tagihan::query()->where('id', $data['id_tagihan'])->update([
                    'status'       => 'sudah bayar',
                    'tanggal_bayar' => now(),
                ]);
            }

            // Jika ada tagihan terkait DAN status menunggu
            // → update tagihan jadi menunggu verifikasi
            if (!empty($data['id_tagihan']) && $data['status'] === 'menunggu') {
                Tagihan::query()->where('id', $data['id_tagihan'])->update([
                    'status' => 'menunggu',
                ]);
            }
        });

        return redirect()->route('pemasukan.index')
            ->with('success', 'Data pemasukan berhasil ditambahkan.');
    }

    public function edit(Pemasukan $pemasukan)
    {
        return view('pemasukan.edit', compact('pemasukan'));
    }

    public function update(Request $request, Pemasukan $pemasukan)
    {
        $data = $request->validate([
            'jenis_pemasukan' => ['required', 'string'],
            'jumlah_bayar'    => ['required', 'integer', 'min:0'],
            'metode_bayar'    => ['required', 'string'],
            'status'          => ['required', 'in:menunggu,lunas'],
            'bulan_tagihan'   => ['nullable', 'string'],
            'tahun_tagihan'   => ['nullable', 'string'],
            'tanggal_bayar'   => ['nullable', 'date'],
            'keterangan'      => ['nullable', 'string'],
            'bukti_bayar'     => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        if ($request->hasFile('bukti_bayar')) {
            if ($pemasukan->bukti_bayar) {
                Storage::disk('public')->delete($pemasukan->bukti_bayar);
            }
            $data['bukti_bayar'] = $request->file('bukti_bayar')
                ->store('bukti_bayar', 'public');
        }

        $pemasukan->update($data);

        return redirect()->route('pemasukan.index')
            ->with('success', 'Data pemasukan berhasil diperbarui.');
    }

    public function destroy(Pemasukan $pemasukan)
    {
        if ($pemasukan->bukti_bayar) {
            Storage::disk('public')->delete($pemasukan->bukti_bayar);
        }
        $pemasukan->delete($pemasukan->id);

        return redirect()->route('pemasukan.index')
            ->with('success', 'Data pemasukan berhasil dihapus.');
    }
}
