<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pertanyaan;
use Illuminate\Support\Facades\Validator;

class PertanyaanController extends Controller
{

    public function index()
    {
        $pertanyaans = Pertanyaan::orderBy('urutan', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $pertanyaans
        ]);
    }

    /**
     * Menyimpan FAQ baru dengan validasi nomor urut unik.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pertanyaan' => 'required|string',
            'jawaban'    => 'required|string',
            'kategori'   => 'required|in:Teknis,Pembayaran,Akun,Promo',
            'urutan'     => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Cek duplikasi nomor urut seperti di Flutter
        if (Pertanyaan::where('urutan', $request->urutan)->exists()) {
            return response()->json([
                'success' => false,
                'message' => "Nomor urut {$request->urutan} sudah digunakan. Silakan pilih nomor lain."
            ], 422);
        }

        $pertanyaan = Pertanyaan::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'FAQ berhasil ditambahkan',
            'data' => $pertanyaan
        ], 201);
    }

    /**
     * Memperbarui data FAQ.
     */
    public function update(Request $request, $id)
    {
        $pertanyaan = Pertanyaan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'pertanyaan' => 'required|string',
            'jawaban'    => 'required|string',
            'kategori'   => 'required|in:Teknis,Pembayaran,Akun,Promo',
            'urutan'     => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Cek duplikasi urutan (kecuali untuk ID ini sendiri)[cite: 10]
        $duplicate = Pertanyaan::where('urutan', $request->urutan)->where('id', '!=', $id)->exists();
        if ($duplicate) {
            return response()->json([
                'success' => false,
                'message' => "Nomor urut {$request->urutan} sudah digunakan."
            ], 422);
        }

        $pertanyaan->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Pertanyaan berhasil diperbarui'
        ]);
    }

    /**
     * Menghapus Pertanyaan[cite: 10].
     */
    public function destroy($id)
    {
        $pertanyaan = Pertanyaan::findOrFail($id);
        $pertanyaan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pertanyaan berhasil dihapus'
        ]);
    }
}
