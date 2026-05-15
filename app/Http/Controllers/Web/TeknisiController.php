<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Teknisi;
use Illuminate\Http\Request;

class TeknisiController extends Controller
{
    public function index()
    {
        $teknisis = Teknisi::orderByDesc('id')->paginate(10);
        return view('teknisi.index', compact('teknisis'));
    }
    public function create()
    {

        return view('teknisi.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required|string', 'no_hp' => 'required|string', 'status' => 'required|in:aktif,tidak aktif']);
        Teknisi::create($request->only('nama', 'no_hp', 'status'));
        return redirect()->route('teknisi.index')->with('success', 'Teknisi berhasil ditambahkan.');
    }
    public function edit(Teknisi $teknisi)
    {
        return view('teknisi.edit', compact('teknisi'));
    }
    public function update(Request $request, Teknisi $teknisi)
    {
        $request->validate(['nama' => 'required|string', 'no_hp' => 'required|string', 'status' => 'required|in:aktif,tidak aktif']);
        $teknisi->update($request->only('nama', 'no_hp', 'status'));
        return redirect()->route('teknisi.index')->with('success', 'Teknisi berhasil diperbarui.');
    }
    public function destroy(Teknisi $teknisi)
    {
        $teknisi->delete($teknisi->id);

        return redirect()->route('teknisi.index')->with('success', 'Teknisi berhasil dihapus.');
    }
}
