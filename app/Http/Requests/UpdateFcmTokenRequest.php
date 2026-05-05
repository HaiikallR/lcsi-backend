<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFcmTokenRequest extends FormRequest
{
   public function authorize(): bool
{
    // Pastikan user sudah login sebelum mengecek ID
    $currentUser = $this->user();
    
    if (!$currentUser) {
        return false;
    }

    // Bandingkan ID dari route pelanggan dengan ID user yang sedang login
    return (int) $this->route('pelanggan') === $currentUser->id;
}

    public function rules(): array
    {
        return [
            'device_token' => ['required', 'string', 'min:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'device_token.required' => 'Device token wajib diisi.',
            'device_token.min' => 'Device token tidak valid (terlalu pendek).',
        ];
    }
}
