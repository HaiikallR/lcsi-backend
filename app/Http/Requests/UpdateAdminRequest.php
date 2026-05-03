<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', Rule::unique('admins')->ignore($this->route('admin'))],
            'password' => ['nullable', 'string', 'min:8'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'  => 'Nama wajib diisi.',
            'nama.max'       => 'Nama maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.unique'   => 'Email sudah digunakan admin lain.',
            'password.min'   => 'Password minimal 8 karakter.',
        ];
    }
}
