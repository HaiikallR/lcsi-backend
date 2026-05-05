<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotifikasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_pelanggan' => ['sometimes', 'integer', 'exists:pelanggans,id'],
            'judul' => ['sometimes', 'string', 'max:255'],
            'pesan' => ['sometimes', 'string'],
            'kategori' => ['sometimes', 'in:info,warning,error,success'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_pelanggan.exists' => 'Pelanggan tidak ditemukan.',
            'judul.max' => 'Judul notifikasi maksimal 255 karakter.',
            'kategori.in' => 'Kategori harus info, warning, error, atau success.',
        ];
    }
}
