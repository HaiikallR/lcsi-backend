<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pertanyaan;
use Illuminate\Http\Request;

class PertanyaanController extends Controller
{
    public function index()
    {
        $pertanyaans = Pertanyaan::orderByDesc('id')->paginate(10);

        // Ambil semua kategori unik untuk filter
        $kategoris = Pertanyaan::whereNotNull('kategori')
            ->distinct()
            ->pluck('kategori');

        return view('pertanyaan.index', compact('pertanyaans', 'kategoris'));
    }

    public function create()
    {
        // Ambil kategori yang sudah ada untuk suggestion
        $kategoris = Pertanyaan::whereNotNull('kategori')
            ->distinct()
            ->pluck('kategori');

        return view('pertanyaan.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pertanyaan' => ['required', 'string', 'max:500'],
            'jawaban'    => ['required', 'string'],
            'kategori'   => ['nullable', 'string', 'max:100'],
        ]);

        Pertanyaan::create($request->only('pertanyaan', 'jawaban', 'kategori'));

        return redirect()->route('pertanyaan.index')
            ->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function edit(Pertanyaan $pertanyaan)
    {
        $kategoris = Pertanyaan::whereNotNull('kategori')
            ->distinct()
            ->pluck('kategori');

        return view('pertanyaan.edit', compact('pertanyaan', 'kategoris'));
    }

    public function update(Request $request, Pertanyaan $pertanyaan)
    {
        $request->validate([
            'pertanyaan' => ['required', 'string', 'max:500'],
            'jawaban'    => ['required', 'string'],
            'kategori'   => ['nullable', 'string', 'max:100'],
        ]);

        $pertanyaan->update($request->only('pertanyaan', 'jawaban', 'kategori'));

        return redirect()->route('pertanyaan.index')
            ->with('success', 'FAQ berhasil diperbarui.');
    }

    public function destroy(Pertanyaan $pertanyaan)
    {
        $pertanyaan->delete();

        return redirect()->route('pertanyaan.index')
            ->with('success', 'FAQ berhasil dihapus.');
    }
}
