<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PelangganAuthController extends Controller
{
    /**
     * Login pelanggan → return token Sanctum
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $pelanggan = Pelanggan::query()->where('email', $request->email)->first();

        if (!$pelanggan || !Hash::check($request->password, $pelanggan->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        if ($pelanggan->status !== 'aktif') {
            throw ValidationException::withMessages([
                'email' => ['Akun kamu tidak aktif. Hubungi admin.'],
            ]);
        }

        // Hapus token lama
        $pelanggan->tokens()->delete();

        // Buat token baru
        $token = $pelanggan->createToken('token-pelanggan')->plainTextToken;

        return response()->json([
            'token'     => $token,
            'pelanggan' => [
                'id'              => $pelanggan->id,
                'nama'            => $pelanggan->nama,
                'email'           => $pelanggan->email,
                'no_hp'           => $pelanggan->no_hp,
                'alamat'          => $pelanggan->alamat,
                'paket_langganan' => $pelanggan->paket_langganan,
                'status'          => $pelanggan->status,
            ],
        ]);
    }

    /**
     * Logout pelanggan → hapus token
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            // Kita ambil tokennya dulu
            $token = $user->currentAccessToken();

            // Cek apakah tokennya benar-benar ada sebelum di-delete
            if (method_exists($token, 'delete')) {
                $token->delete();
            } else {
                // Jika currentAccessToken gagal, kita hapus semua token user tersebut (lebih aman)
                $user->tokens()->delete();
            }

            return response()->json(['pesan' => 'Logout berhasil.']);
        }

        return response()->json(['pesan' => 'User tidak terautentikasi.'], 401);
    }
    /**
     * Data profil pelanggan yang sedang login
     */
    public function me(Request $request)
    {
        return response()->json([
            'pelanggan' => $request->user(),
        ]);
    }
    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'device_token' => ['required', 'string'],
        ]);

        $request->user()->update([
            'device_token' => $request->device_token,
        ]);

        return response()->json([
            'message' => 'Device token berhasil diperbarui.',
        ]);
    }
}
