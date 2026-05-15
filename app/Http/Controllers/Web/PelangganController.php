<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelanggan::query();

        // Filter search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama',  'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pelanggans = $query->orderByDesc('id')->paginate(10)->withQueryString();

        // Statistik
        $total = [
            'semua'    => Pelanggan::count(),
            'aktif'    => Pelanggan::where('status', 'aktif')->count(),
            'nonaktif' => Pelanggan::where('status', 'tidak aktif')->count(),
            'paket'    => Pelanggan::distinct('paket_langganan')->count('paket_langganan'),
        ];

        return view('pelanggan.index', compact('pelanggans', 'total'));
    }

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', 'unique:pelanggans,email'],
            'no_hp'           => ['required', 'string'],
            'alamat'          => ['required', 'string'],
            'paket_langganan' => ['required', 'string'],
            'status'          => ['required', 'in:aktif,tidak aktif'],
            'password'        => ['required', 'min:8'],
        ]);

        $data['password'] = Hash::make($data['password']);
        Pelanggan::create($data);

        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function show(Pelanggan $pelanggan)
    {
        $pelanggan->load(['tiket.teknisi', 'tagihan', 'perangkat']);
        return view('pelanggan.show', compact('pelanggan'));
    }

    public function edit(Pelanggan $pelanggan)
    {
        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $data = $request->validate([
            'nama'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', 'unique:pelanggans,email,' . $pelanggan->id],
            'no_hp'           => ['required', 'string'],
            'alamat'          => ['required', 'string'],
            'paket_langganan' => ['required', 'string'],
            'status'          => ['required', 'in:aktif,tidak aktif'],
            'password'        => ['nullable', 'min:8'],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $pelanggan->update($data);

        return redirect()->route('pelanggan.index')
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();
        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }
}
