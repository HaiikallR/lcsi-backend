<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    /**
     * METHOD: index()
     * Dipanggil saat: GET /tagihan
     * Tugasnya: Ambil semua tagihan dari DB, kirim ke view
     */
    public function index()
    {
        /**
         * Tagihan::with('pelanggan:id,nama')
         * ↑ Ini "eager loading" — ambil data tagihan SEKALIGUS
         *   data pelanggannya (nama saja, bukan semua kolom)
         *   agar tidak terjadi N+1 query problem
         *
         * ->orderByDesc('id')
         * ↑ Urutkan dari yang terbaru
         *
         * ->paginate(10)
         * ↑ Batasi 10 data per halaman, otomatis buat tombol
         *   navigasi halaman (next/prev)
         */
        $tagihans = Tagihan::with('pelanggan:id,nama')
            ->orderByDesc('id')
            ->paginate(10);

        /**
         * return view('tagihan.index', compact('tagihans'))
         * ↑ Kirim variabel $tagihans ke file:
         *   resources/views/tagihan/index.blade.php
         *
         * compact('tagihans') = ['tagihans' => $tagihans]
         * Di dalam view, kamu akses dengan: $tagihans
         */
        return view('tagihan.index', compact('tagihans'));
    }

    /**
     * METHOD: create()
     * Dipanggil saat: GET /tagihan/create
     * Tugasnya: Tampilkan form kosong untuk input tagihan baru
     */
    public function create()
    {
        /**
         * Kita butuh daftar pelanggan untuk dropdown di form.
         * Ambil hanya pelanggan aktif, urutkan nama A-Z.
         */
        $pelanggans = Pelanggan::whereStatus('aktif')
            ->oldest('nama')
            ->get();

        // Kirim $pelanggans ke view create
        return view('tagihan.create', compact('pelanggans'));
    }

    /**
     * METHOD: store()
     * Dipanggil saat: POST /tagihan (tombol submit form create)
     * Tugasnya: Validasi input lalu simpan ke database
     */
    public function store(Request $request)
    {
        /**
         * $data = $request->validate([...])
         * ↑ Laravel cek semua input sesuai aturan.
         *   Jika gagal → otomatis redirect balik ke form
         *   dengan pesan error. Tidak perlu if/else manual.
         *
         * Aturan validasi:
         * 'required'          → wajib diisi
         * 'exists:tabel,kolom'→ nilai harus ada di tabel tersebut
         * 'in:a,b,c'          → nilai harus salah satu dari a, b, atau c
         * 'integer'           → harus angka
         * 'nullable'          → boleh kosong
         */
        $data = $request->validate([
            'id_pelanggan' => ['required', 'exists:pelanggans,id'],
            'jumlah'       => ['required', 'integer', 'min:0'],
            'bulan'        => ['required', 'string'],
            'tahun'        => ['required', 'integer'],
            'status'       => ['required', 'in:belum bayar,menunggu,sudah bayar'],
            'catatan'      => ['nullable', 'string'],
        ]);

        // ✅ CEK: tagihan di periode yang sama sudah ada?
        $sudahAda = Tagihan::query()
            ->where('id_pelanggan', $data['id_pelanggan'])
            ->where('bulan', $data['bulan'])
            ->where('tahun', $data['tahun'])
            ->exists();

        if ($sudahAda) {
            return back()
                ->withInput()
                ->withErrors([
                    'bulan' => 'Pelanggan ini sudah memiliki tagihan untuk periode ' . $data['bulan'] . ' ' . $data['tahun'] . '.'
                ]);
        }
        /**
         * Tagihan::create([...])
         * ↑ Simpan data baru ke tabel tagihans
         *   Hanya kolom yang ada di $fillable model
         *   yang akan disimpan.
         */
        Tagihan::create($request->only(
            'id_pelanggan', 'jumlah', 'bulan', 'tahun', 'status', 'catatan'
        ));

        /**
         * redirect()->route('tagihan.index')
         * ↑ Setelah simpan, arahkan ke halaman daftar tagihan
         *
         * ->with('success', '...')
         * ↑ Kirim pesan flash ke halaman berikutnya
         *   Di layout kita sudah pasang <x-ui.flash-message />
         *   yang akan tampilkan pesan ini otomatis
         */
        return redirect()->route('tagihan.index')
            ->with('success', 'Tagihan berhasil dibuat.');
    }

    /**
     * METHOD: edit()
     * Dipanggil saat: GET /tagihan/{id}/edit
     * Tugasnya: Tampilkan form yang sudah terisi data tagihan
     *
     * Route Model Binding: Laravel otomatis cari Tagihan
     * berdasarkan {id} di URL dan inject ke parameter $tagihan.
     * Tidak perlu Tagihan::find($id) manual.
     */
    public function edit(Tagihan $tagihan)
    {
        // $tagihan sudah otomatis berisi data dari DB
        return view('tagihan.edit', compact('tagihan'));
    }

    /**
     * METHOD: update()
     * Dipanggil saat: PUT /tagihan/{id} (tombol submit form edit)
     * Tugasnya: Validasi lalu update data yang sudah ada
     */
    public function update(Request $request, Tagihan $tagihan)
    {
        $data = $request->validate([
            'jumlah'  => ['required', 'integer', 'min:0'],
            'bulan'   => ['required', 'string'],
            'tahun'   => ['required', 'integer'],
            'status'  => ['required', 'in:belum bayar,menunggu,sudah bayar'],
            'catatan' => ['nullable', 'string'],
        ]);


        $sudahAda = Tagihan::query()
            ->where('id_pelanggan', $tagihan->id_pelanggan)
            ->where('bulan', $data['bulan'])
            ->where('tahun', $data['tahun'])
            ->where('id', '!=', $tagihan->id) // Kecuali tagihan yang sedang diedit
            ->exists();

        if ($sudahAda) {
            return back()
                ->withInput()
                ->withErrors([
                    'bulan' => 'Pelanggan ini sudah memiliki tagihan untuk periode ' . $data['bulan'] . ' ' . $data['tahun'] . '.'
                ]);
        }
        /**
         * Otomatis isi tanggal_bayar saat status jadi 'sudah bayar'
         */
        if ($data['status'] === 'sudah bayar' && $tagihan->status !== 'sudah bayar') {
            $data['tanggal_bayar'] = now();
        }

        // Update kolom yang berubah saja
        $tagihan->update($data);

        return redirect()->route('tagihan.index')
            ->with('success', 'Tagihan berhasil diperbarui.');
    }

    /**
     * METHOD: destroy()
     * Dipanggil saat: DELETE /tagihan/{id} (tombol hapus)
     */
    public function destroy(Tagihan $tagihan)
    {
        $tagihan->delete($tagihan->id);
        return redirect()->route('tagihan.index')
            ->with('success', 'Tagihan berhasil dihapus.');
    }

    public function formMassal()
    {
        // Hitung berapa pelanggan aktif yang ada
        $totalPelanggan = Pelanggan::query()->where('status', 'aktif')->count();
        return view('tagihan.massal', compact('totalPelanggan'));
    }

    /**
     * Proses buat tagihan massal
     * POST /tagihan/massal
     */
    public function storeMassal(Request $request)
    {
        $request->validate([
            'jumlah' => ['required', 'integer', 'min:1000'],
            'bulan'  => ['required', 'string'],
            'tahun'  => ['required', 'integer', 'min:2020'],
            'catatan'=> ['nullable', 'string'],
        ]);

        // Ambil semua pelanggan aktif
        $pelanggans = Pelanggan::query()->where('status', 'aktif')->get();

        $berhasil = 0;
        $diskip   = 0;
        $namaDiskip = [];

        foreach ($pelanggans as $pelanggan) {

            // ✅ CEK: sudah ada tagihan di periode ini untuk pelanggan ini?
            $sudahAda = Tagihan::query()
                ->where('id_pelanggan', $pelanggan->id)
                ->where('bulan', $request->bulan)
                ->where('tahun', $request->tahun)
                ->exists();

            if ($sudahAda) {
                // Skip pelanggan ini
                $diskip++;
                $namaDiskip[] = $pelanggan->nama;
                continue; // lanjut ke pelanggan berikutnya
            }

            // Buat tagihan untuk pelanggan ini
            Tagihan::query()->create([
                'id_pelanggan' => $pelanggan->id,
                'jumlah'       => $request->jumlah,
                'bulan'        => $request->bulan,
                'tahun'        => $request->tahun,
                'status'       => 'belum bayar',
                'catatan'      => $request->catatan,
            ]);

            $berhasil++;
        }

        // Buat pesan ringkasan
        $pesan = "Tagihan berhasil dibuat untuk {$berhasil} pelanggan.";

        if ($diskip > 0) {
            $pesan .= " {$diskip} pelanggan di-skip karena sudah memiliki tagihan periode {$request->bulan} {$request->tahun}: " . implode(', ', $namaDiskip) . ".";
        }

        return redirect()->route('tagihan.index')
            ->with('success', $pesan);
    }
}