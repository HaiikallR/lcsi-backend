<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNotifikasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_pelanggan' => ['required', 'integer', 'exists:pelanggans,id'],
            'judul' => ['required', 'string', 'max:255'],
            'pesan' => ['required', 'string'],
            'kategori' => ['required', 'in:info,warning,error,success'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_pelanggan.required' => 'ID pelanggan wajib diisi.',
            'id_pelanggan.exists' => 'Pelanggan tidak ditemukan.',
            'judul.required' => 'Judul notifikasi wajib diisi.',
            'judul.max' => 'Judul notifikasi maksimal 255 karakter.',
            'pesan.required' => 'Pesan notifikasi wajib diisi.',
            'kategori.required' => 'Kategori notifikasi wajib dipilih.',
            'kategori.in' => 'Kategori harus info, warning, error, atau success.',
        ];
    }
}
