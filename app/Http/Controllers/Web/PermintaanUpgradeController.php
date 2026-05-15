<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PermintaanUpgrade;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PermintaanUpgradeController extends Controller
{
    public function index()
    {
        $upgrades = PermintaanUpgrade::with('pelanggan:id,nama,email,paket_langganan')
            ->orderByDesc('id')
            ->paginate(10);

        $statistik = [
            'menunggu'  => PermintaanUpgrade::query()->where('status', 'menunggu')->count(),
            'disetujui' => PermintaanUpgrade::query()->where('status', 'disetujui')->count(),
            'ditolak'   => PermintaanUpgrade::query()->where('status', 'ditolak')->count(),
        ];

        return view('permintaan-upgrade.index', compact('upgrades', 'statistik'));
    }

    public function show(PermintaanUpgrade $permintaanUpgrade)
    {
        $permintaanUpgrade->load('pelanggan');
        return view('permintaan-upgrade.show', compact('permintaanUpgrade'));
    }

    public function create()
    {
        $pelanggans = Pelanggan::query()->where('status', 'aktif')->orderBy('nama')->get();
        return view('permintaan-upgrade.create', compact('pelanggans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pelanggan' => ['required', 'exists:pelanggans,id'],
            'paket_lama'   => ['required', 'string'],
            'paket_baru'   => ['required', 'string'],
            'harga_baru'   => ['required', 'numeric', 'min:0'],
        ]);

        PermintaanUpgrade::create([
            'id_pelanggan' => $request->id_pelanggan,
            'paket_lama'   => $request->paket_lama,
            'paket_baru'   => $request->paket_baru,
            'harga_baru'   => $request->harga_baru,
            'status'       => 'menunggu',
        ]);

        return redirect()->route('permintaan-upgrade.index')
            ->with('success', 'Permintaan upgrade berhasil ditambahkan.');
    }

    public function edit(PermintaanUpgrade $permintaanUpgrade)
    {
        return view('permintaan-upgrade.edit', compact('permintaanUpgrade'));
    }

    public function update(Request $request, PermintaanUpgrade $permintaanUpgrade)
    {
        $request->validate([
            'paket_lama' => ['required', 'string'],
            'paket_baru' => ['required', 'string'],
            'harga_baru' => ['required', 'numeric', 'min:0'],
            'status'     => ['required', 'in:menunggu,disetujui,ditolak'],
        ]);

        $permintaanUpgrade->update($request->only(
            'paket_lama',
            'paket_baru',
            'harga_baru',
            'status'
        ));

        return redirect()->route('permintaan-upgrade.index')
            ->with('success', 'Permintaan upgrade berhasil diperbarui.');
    }

    public function destroy(PermintaanUpgrade $permintaanUpgrade)
    {
        $permintaanUpgrade->delete($permintaanUpgrade->id);
        return redirect()->route('permintaan-upgrade.index')
            ->with('success', 'Permintaan upgrade berhasil dihapus.');
    }

    /**
     * Setujui permintaan upgrade
     * → Update status pelanggan & paket langganan
     */
    public function setujui(PermintaanUpgrade $permintaanUpgrade)
    {
        // Pastikan masih menunggu
        if ($permintaanUpgrade->status !== 'menunggu') {
            return redirect()->route('permintaan-upgrade.index')
                ->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        // Update status permintaan
        $permintaanUpgrade->update([
            'status'         => 'disetujui',
            'disetujui_pada' => now(),
            'ditolak_pada'   => null,
        ]);

        // Update paket pelanggan ke paket baru
        $permintaanUpgrade->pelanggan->update([
            'paket_langganan' => $permintaanUpgrade->paket_baru,
        ]);

        return redirect()->route('permintaan-upgrade.index')
            ->with('success', 'Permintaan upgrade ' . $permintaanUpgrade->pelanggan->nama . ' berhasil disetujui. Paket diperbarui ke ' . $permintaanUpgrade->paket_baru . '.');
    }

    /**
     * Tolak permintaan upgrade
     */
    public function tolak(Request $request, PermintaanUpgrade $permintaanUpgrade)
    {
        $request->validate([
            'alasan_tolak' => ['required', 'string', 'min:5'],
        ]);

        if ($permintaanUpgrade->status !== 'menunggu') {
            return redirect()->route('permintaan-upgrade.index')
                ->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        $permintaanUpgrade->update([
            'status'       => 'ditolak',
            'ditolak_pada' => now(),
            'catatan'      => $request->alasan_tolak,
        ]);

        return redirect()->route('permintaan-upgrade.index')
            ->with('success', 'Permintaan upgrade ' . $permintaanUpgrade->pelanggan->nama . ' berhasil ditolak.');
    }
}
