<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Perangkat;
use Illuminate\Http\Request;

class PerangkatController extends Controller
{
    public function index()
    {
        $perangkats = Perangkat::with('pelanggan:id,nama')
            ->orderByDesc('id')->paginate(10);
        return view('perangkat.index', compact('perangkats'));
    }

    public function create()
    {
        $pelanggans = Pelanggan::orderBy('nama')->get(['id', 'nama', 'paket_langganan']);
        return view('perangkat.create', compact('pelanggans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_perangkat' => ['required', 'string', 'max:255'],
            'merk'           => ['required', 'string', 'max:255'],
            'serial_number'  => ['required', 'string', 'unique:perangkats,serial_number'],
            'status'         => ['required', 'in:tersedia,digunakan,perbaikan'],
            'id_pelanggan'   => ['nullable', 'exists:pelanggans,id'],
        ]);

        Perangkat::create($data);

        return redirect()->route('perangkat.index')
            ->with('success', 'Perangkat berhasil ditambahkan.');
    }

    public function edit(Perangkat $perangkat)
    {
        $pelanggans = Pelanggan::orderBy('nama')->get(['id', 'nama', 'paket_langganan']);
        return view('perangkat.edit', compact('perangkat', 'pelanggans'));
    }

    public function update(Request $request, Perangkat $perangkat)
    {
        $data = $request->validate([
            'nama_perangkat' => ['required', 'string', 'max:255'],
            'merk'           => ['required', 'string', 'max:255'],
            'serial_number'  => ['required', 'string', 'unique:perangkats,serial_number,' . $perangkat->id],
            'status'         => ['required', 'in:tersedia,digunakan,perbaikan'],
            'id_pelanggan'   => ['nullable', 'exists:pelanggans,id'],
        ]);

        $perangkat->update($data);

        return redirect()->route('perangkat.index')
            ->with('success', 'Perangkat berhasil diperbarui.');
    }

    public function destroy(Perangkat $perangkat)
    {
        $perangkat->delete();
        return redirect()->route('perangkat.index')
            ->with('success', 'Perangkat berhasil dihapus.');
    }
}