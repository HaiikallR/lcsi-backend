<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Perangkat;
use Illuminate\Support\Facades\Validator;


class PerangkatController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $devices = Perangkat::when($search, function ($query, $search) {
            return $query->where('serial_number', 'like', "%{$search}%")
                ->orWhere('nama_perangkat', 'like', "%{$search}%");
        })->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $devices
        ]);
    }

    /**
     * Menyimpan perangkat baru dengan validasi SN unik.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'serial_number'  => 'required|string|unique:perangkats,serial_number',
            'nama_perangkat' => 'required|string',
            'merk'           => 'required|string',
            'status'         => 'required|in:Tersedia,Terpasang,Rusak',
            'assigned_to'    => 'required_if:status,Terpasang',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak lengkap atau SN sudah terdaftar',
                'errors'  => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        // Logika default assigned_to sesuai Flutter
        if ($request->status !== 'Terpasang') {
            $data['assigned_to'] = 'Gudang';
        }

        $device = Perangkat::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Perangkat berhasil ditambahkan ke inventaris',
            'data'    => $device
        ], 201);
    }

    /**
     * Memperbarui data perangkat.
     */
    public function update(Request $request, $id)
    {
        $device = Perangkat::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'serial_number'  => "required|string|unique:perangkat,serial_number,{$id}",
            'nama_perangkat' => 'required|string',
            'merk'           => 'required|string',
            'status'         => 'required|in:Tersedia,Terpasang,Rusak',
            'assigned_to'    => 'required_if:status,Terpasang',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        if ($request->status !== 'Terpasang') {
            $data['assigned_to'] = 'Gudang';
        }

        $device->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Data perangkat berhasil diperbarui'
        ]);
    }

    /**
     * Menghapus perangkat dari inventaris[cite: 11].
     */
    public function destroy($id)
    {
        $device = Perangkat::findOrFail($id);
        $device->delete();

        return response()->json([
            'success' => true,
            'message' => 'Perangkat berhasil dihapus'
        ]);
    }
}
