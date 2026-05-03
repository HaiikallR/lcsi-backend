<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeknisiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:25'],
            'status' => ['required', 'in:aktif,siap,tidak aktif'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama teknisi wajib diisi.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'status.required' => 'Status wajib diisi.',
            'status.in' => 'Status harus aktif, siap, atau tidak aktif.',
        ];
    }
}
