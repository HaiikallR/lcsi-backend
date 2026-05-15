<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use App\Models\Pelanggan;
use App\Services\FcmNotificationService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotifikasiController extends Controller
{
    /**
     * Inject FcmNotificationService via constructor
     * Laravel otomatis resolve dependency ini
     */
    public function __construct(
        private FcmNotificationService $fcmService
    ) {}

    public function index()
    {
        $notifikasis = Notifikasi::with('pelanggan:id,nama')
            ->orderByDesc('id')
            ->paginate(10);

        $statistik = [
            'info'    => Notifikasi::query()->where('kategori', 'info')->count(),
            'warning' => Notifikasi::query()->where('kategori', 'warning')->count(),
            'error'   => Notifikasi::query()->where('kategori', 'error')->count(),
            'success' => Notifikasi::query()->where('kategori', 'success')->count(),
        ];

        return view('notifikasi.index', compact('notifikasis', 'statistik'));
    }

    public function create()
    {
        $pelanggans = Pelanggan::query()->where('status', 'aktif')
            ->orderBy('nama')
            ->get(['id', 'nama', 'paket_langganan', 'device_token']);

        return view('notifikasi.create', compact('pelanggans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_pelanggan' => ['required', 'exists:pelanggans,id'],
            'judul'        => ['required', 'string', 'max:100'],
            'pesan'        => ['required', 'string'],
            'kategori'     => ['required', 'in:info,success,warning,error'],
        ]);

        // 1. Cari pelanggan
        $pelanggan = Pelanggan::findOrFail($data['id_pelanggan']);

        // 2. Simpan ke database
        $notifikasi = Notifikasi::create($data);

        // 3. Kirim via FCM jika pelanggan punya device token
        $fcmBerhasil = false;

        if (!empty($pelanggan->device_token)) {
            try {
                $this->fcmService->kirim(
                    fcmToken: $pelanggan->device_token,
                    judul: $data['judul'],
                    pesan: $data['pesan'],
                    data: [
                        'kategori'      => $data['kategori'],
                        'notifikasi_id' => (string) $notifikasi->id,
                        'timestamp'     => now()->toIso8601String(),
                    ]
                );
                $fcmBerhasil = true;
            } catch (Exception $e) {
                // FCM gagal tapi notifikasi tetap tersimpan di DB
                Log::error('FCM Web send failed: ' . $e->getMessage(), [
                    'notifikasi_id' => $notifikasi->id,
                    'pelanggan_id'  => $pelanggan->id,
                ]);
            }
        }

        // Pesan berbeda tergantung kondisi FCM
        if (empty($pelanggan->device_token)) {
            $pesan = 'Notifikasi tersimpan, tapi ' . $pelanggan->nama .
                ' belum memiliki device token — push notification tidak terkirim.';
            return redirect()->route('notifikasi.index')->with('warning', $pesan);
        }

        if (!$fcmBerhasil) {
            $pesan = 'Notifikasi tersimpan, tapi push notification gagal dikirim. Cek log untuk detail.';
            return redirect()->route('notifikasi.index')->with('warning', $pesan);
        }

        return redirect()->route('notifikasi.index')
            ->with('success', 'Notifikasi berhasil dikirim ke ' . $pelanggan->nama . '!');
    }

    public function destroy(Notifikasi $notifikasi)
    {
        $notifikasi->delete($notifikasi->id);

        return redirect()->route('notifikasi.index')
            ->with('success', 'Notifikasi berhasil dihapus.');
    }

    /**
     * Kirim notifikasi massal ke semua pelanggan aktif
     */
    public function storeMassal(Request $request)
    {
        $data = $request->validate([
            'judul'    => ['required', 'string', 'max:100'],
            'pesan'    => ['required', 'string'],
            'kategori' => ['required', 'in:info,success,warning,error'],
        ]);

        $pelanggans  = Pelanggan::query()->where('status', 'aktif')->get();
        $berhasil    = 0;
        $gagal       = 0;
        $tanpaToken  = 0;

        foreach ($pelanggans as $pelanggan) {

            // Simpan ke DB untuk setiap pelanggan
            $notifikasi = Notifikasi::create([
                ...$data,
                'id_pelanggan' => $pelanggan->id,
            ]);

            if (empty($pelanggan->device_token)) {
                $tanpaToken++;
                continue;
            }

            try {
                $this->fcmService->kirim(
                    fcmToken: $pelanggan->device_token,
                    judul: $data['judul'],
                    pesan: $data['pesan'],
                    data: [
                        'kategori'      => $data['kategori'],
                        'notifikasi_id' => (string) $notifikasi->id,
                        'timestamp'     => now()->toIso8601String(),
                    ]
                );
                $berhasil++;
            } catch (Exception $e) {
                $gagal++;
                Log::error('FCM Massal failed: ' . $e->getMessage(), [
                    'pelanggan_id' => $pelanggan->id,
                ]);
            }
        }

        $pesan = "Notifikasi massal selesai. "
            . "Berhasil: {$berhasil}, "
            . "Gagal: {$gagal}, "
            . "Tanpa token: {$tanpaToken}.";

        return redirect()->route('notifikasi.index')->with('success', $pesan);
    }
}
