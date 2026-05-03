<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePelangganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('pelanggans')->ignore($this->route('pelanggan'))],
            'password' => ['nullable', 'string', 'min:8'],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
            'paket_langganan' => ['required', 'string', 'max:100'],
            'status' => ['required', 'in:aktif,tidak aktif'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan pelanggan lain.',
            'password.min' => 'Password minimal 8 karakter.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'paket_langganan.required' => 'Paket langganan wajib diisi.',
            'status.required' => 'Status wajib diisi.',
            'status.in' => 'Status harus aktif atau tidak aktif.',
        ];
    }
}
