<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    /**
     * Menangani proses login admin LCSI.
     */
    public function loginAdmin(Request $request)
    {
        // 1. Validasi Input sesuai controller Flutter
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Email dan Password wajib diisi',
            ], 422);
        }

        // 2. Cari User berdasarkan email
        $user = Admin::where('email', $request->email)->first();

        // 3. Cek Kredensial dan Role
        // Pastikan hanya user dengan role 'admin' yang bisa masuk ke dashboard
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password salah'
            ], 401);
        }

        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Anda bukan admin LCSI.'
            ], 403);
        }

        // 4. Generate Token (Sanctum)
        $token = $user->createToken('admin_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login Berhasil',
            'user'    => [
                'id'    => $user->id,
                'nama'  => $user->nama,
                'email' => $user->email,
            ],
            'token'   => $token
        ]);
    }

    /**
     * Logout dan menghapus token.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ]);
    }
}
