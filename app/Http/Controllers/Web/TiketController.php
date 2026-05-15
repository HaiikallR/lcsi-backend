<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Tiket;
use App\Models\Pelanggan;
use App\Models\Teknisi;
use Illuminate\Http\Request;
use App\Services\WhatsappTicketMessageService;

class TiketController extends Controller
{
    public function index()
    {
        $tikets = Tiket::with(['pelanggan:id,nama', 'teknisi:id,nama,no_hp'])
            ->orderByDesc('id')->paginate(10);


        return view('tiket.index', compact('tikets'));
    }

    public function create()
    {
        $pelanggans = Pelanggan::query()->where('status', 'aktif')->orderBy('nama')->get();
        $teknisis   = Teknisi::query()->where('status', '!=', 'tidak aktif')->orderBy('nama')->get();
        return view('tiket.create', compact('pelanggans', 'teknisis'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jenis_pekerjaan'       => ['required', 'string'],
            'id_teknisi'            => ['required', 'exists:teknisis,id'],
            'id_pelanggan'          => ['nullable', 'exists:pelanggans,id'],
            'calon_pelanggan_nama'  => ['nullable', 'string'],
            'calon_pelanggan_no_hp' => ['nullable', 'string'],
            'calon_pelanggan_alamat' => ['nullable', 'string'],
            'ongkos_teknisi'        => ['nullable', 'integer', 'min:0'],
        ]);

        $data['status']          = 'in progress';
        $data['tanggal_selesai'] = null;

        Tiket::create($data);

        return redirect()->route('tiket.index')
            ->with('success', 'Tiket berhasil dibuat.');
    }

    public function show(Tiket $tiket)
    {
        $tiket->load(['pelanggan', 'teknisi', 'pengeluaran']);
        return view('tiket.show', compact('tiket'));
    }

    public function edit(Tiket $tiket)
    {
        $teknisis = Teknisi::query()->orderByDesc('nama')->get();
        return view('tiket.edit', compact('tiket', 'teknisis'));
    }

    public function update(Request $request, Tiket $tiket)
    {
        $data = $request->validate([
            'jenis_pekerjaan' => ['required', 'string'],
            'id_teknisi'      => ['required', 'exists:teknisis,id'],
            'ongkos_teknisi'  => ['nullable', 'integer', 'min:0'],
            'status'          => ['required', 'in:in progress,selesai'],
        ]);

        if ($data['status'] === 'selesai' && $tiket->status !== 'selesai') {
            $data['tanggal_selesai'] = now();
        }

        $tiket->update($data);

        return redirect()->route('tiket.index')
            ->with('success', 'Tiket berhasil diperbarui.');
    }


    public function destroy(Tiket $tiket)
    {
        $tiket->delete($tiket->id);
        return redirect()->route('tiket.index')
            ->with('success', 'Tiket berhasil dihapus.');
    }
    public function whatsapp(Tiket $tiket)
    {
        $tiket->load(['pelanggan', 'teknisi']);


        if (!$tiket->teknisi?->no_hp) {
            return redirect()->route('tiket.show', $tiket)
                ->with('error', 'Teknisi tidak memiliki nomor HP.');
        }


        $service = new WhatsappTicketMessageService();
        $pesan   = $service->buildPesan($tiket);
        $waUrl   = $service->buildUrl($tiket->teknisi->no_hp, $pesan);

        // Redirect langsung ke WhatsApp
        return redirect()->away($waUrl);
    }
}
