<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Tagihan;
use App\Models\Pemasukan;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    /**
     * Menampilkan daftar pelanggan dengan fitur pencarian Nama atau ID Client.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $clients = Pelanggan::where('role', 'pelanggan')
            ->when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('id_client', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $clients
        ]);
    }

    /**
     * Menambah pelanggan baru dengan password default (ID Client).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_client' => 'required|string|unique:pelanggans,id_client',
            'nama'      => 'required|string',
            'email'     => 'required|email|unique:pelanggans,email',
            'no_telp'   => 'required',
            'alamat'    => 'required',
            'paket'     => 'required|in:40,60,100,Business 100',
            'status'    => 'required|in:Aktif,Non-Aktif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Email, ID, dan Nama tidak boleh kosong atau sudah terdaftar',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Simpan data pelanggan baru
        $client = Pelanggan::create([
            'id_client' => $request->id_client,
            'nama'      => $request->nama,
            'email'     => $request->email,
            'no_telp'   => $request->no_telp,
            'alamat'    => $request->alamat,
            'paket'     => $request->paket,
            'status'    => $request->status,
            'role'      => 'pelanggan',
            'password'  => Hash::make($request->id_client), // Password default = ID Client
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan!',
            'data'    => $client
        ], 201);
    }

    /**
     * Memperbarui data profil pelanggan.
     */
    public function update(Request $request, $id)
    {
        $client = Pelanggan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama'    => 'required|string',
            'no_telp' => 'required',
            'alamat'  => 'required',
            'paket'   => 'required',
            'status'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $client->update($request->only(['nama', 'no_telp', 'alamat', 'paket', 'status']));

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui'
        ]);
    }

    /**
     * Hapus Massal: Membersihkan User beserta Tagihan dan Riwayat Pembayarannya.
     */
    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            $client = Pelanggan::findOrFail($id);

            // 1. Cek tagihan aktif (opsional untuk informasi di log)
            $adaTagihanHutang = Tagihan::where('user_id', $id)->where('status', 'Belum Dibayar')->exists();

            // 2. Hapus semua data terkait (Tagihan & Pemasukan/Payments)
            Tagihan::where('user_id', $id)->delete();
            Pemasukan::where('user_id', $id)->delete();

            // 3. Hapus User
            $client->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pelanggan dan seluruh datanya berhasil dibersihkan.'
            ]);
        });
    }
}
