<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     * GET /login
     */
    public function showLogin()
    {
        // Kalau sudah login, langsung ke dashboard
        if (Auth::guard('admin')->check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Proses login
     * POST /login
     */
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Coba login dengan guard admin
        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {

            // Regenerate session untuk keamanan
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        // Login gagal → kembali ke form dengan error
        return back()
            ->withErrors([
                'email' => 'Email atau password salah.',
            ])
            ->onlyInput('email');
    }

    /**
     * Proses logout
     * POST /logout
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        // Hapus session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Berhasil keluar dari sistem.');
    }
}
