<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuthRequest;
use App\Http\Resources\AdminResource;
use App\Http\Resources\MessageResource;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(StoreAuthRequest $request): AdminResource
    {
        $admin = Admin::query()
            ->where('email', $request->validated('email'))
            ->first();

        if (! $admin || ! Hash::check($request->validated('password'), $admin->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        $token = $admin->createToken('token-admin')->plainTextToken;
        $admin->setAttribute('token', $token);

        return new AdminResource($admin);
    }

    public function logout(Request $request): MessageResource
    {
        $request->admin()->currentAccessToken()?->delete();

        return new MessageResource([
            'pesan' => 'Logout berhasil.',
        ]);
    }
}
