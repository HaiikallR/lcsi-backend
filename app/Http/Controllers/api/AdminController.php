<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminCollection;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;


class AdminController extends Controller
{
    public function index()
    {
        return new AdminCollection(Admin::orderByDesc('id')->paginate(20));
    }

    /**
     * Menambah Admin baru dengan password default "admin123".
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'  => 'required|string',
            'email' => 'required|email|unique:admins,email',
            'status' => 'required|in:Aktif,Suspended',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal: Email sudah terdaftar atau data tidak lengkap',
                'errors'  => $validator->errors()
            ], 422);
        }

        $admin = Admin::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'role'     => 'Staff Admin', // Sesuai logika Flutter
            'status'   => $request->status,
            'password' => Hash::make('admin123'), // Password default
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Admin Berhasil Disimpan',
            'data'    => $admin
        ], 201);
    }

    /**
     * Memperbarui data admin (Nama dan Status).
     */
    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama'   => 'required|string',
            'status' => 'required|in:Aktif,Suspended',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $admin->update([
            'nama'   => $request->nama,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Admin Berhasil Diperbarui',
        ]);
    }

    /**
     * Menghapus akun admin.
     */
    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Akun admin berhasil dihapus'
        ]);
    }
}
