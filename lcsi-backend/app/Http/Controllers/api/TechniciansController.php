<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teknisi;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TechniciansController extends Controller
{
    /**
     * Mengambil semua data teknisi dengan fitur pencarian.
     * Sesuai dengan filter di _buildSearchBar pada Flutter.
     */
    public function index(Request $request)
    {
        $query = $request->get('search');

        $technicians = Teknisi::when($query, function ($q) use ($query) {
            return $q->where('nama', 'like', "%$query%")
                ->orWhere('wilayah', 'like', "%$query%");
        })->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $technicians
        ]);
    }

    /**
     * Menyimpan data teknisi baru.
     * Mencakup validasi ID unik sesuai logika Flutter.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_teknisi' => 'required|unique:teknisis,id_teknisi', // Validasi ID Ganda
            'nama'       => 'required|string',
            'no_hp'      => 'required|numeric',
            'wilayah'    => 'required|string',
            'status'     => 'required|in:Tersedia,Bertugas,Libur',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $teknisi = Teknisi::create($request->all());

        return response()->json([
            'success' => true,
            'message' => "Data teknisi {$teknisi->nama} berhasil disimpan",
            'data'    => $teknisi
        ], 201);
    }

    /**
     * Update data teknisi berdasarkan ID.
     */
    public function update(Request $request, $id)
    {
        $teknisi = Teknisi::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'id_teknisi' => 'required|unique:teknisis,id_teknisi,' . $id,
            'nama'       => 'required|string',
            'no_hp'      => 'required|numeric',
            'wilayah'    => 'required|string',
            'status'     => 'required|in:Tersedia,Bertugas,Libur',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $teknisi->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Update data teknisi berhasil',
            'data'    => $teknisi
        ]);
    }

    /**
     * Menghapus data teknisi.
     */
    public function destroy($id)
    {
        $teknisi = Teknisi::findOrFail($id);
        $teknisi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data teknisi berhasil dihapus'
        ]);
    }
}
