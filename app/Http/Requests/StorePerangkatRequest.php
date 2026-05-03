<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePerangkatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_perangkat' => ['required', 'string', 'max:255'],
            'merk' => ['required', 'string', 'max:100'],
            'serial_number' => ['required', 'string', 'max:100', 'unique:perangkats,serial_number'],
            'status' => ['required', 'in:tersedia,digunakan,perbaikan'],
            'id_pelanggan' => ['required', 'exists:pelanggans,id', 'unique:perangkats,id_pelanggan'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_perangkat.required' => 'Nama perangkat wajib diisi.',
            'merk.required' => 'Merk wajib diisi.',
            'serial_number.required' => 'Serial number wajib diisi.',
            'serial_number.unique' => 'Serial number sudah terdaftar.',
            'status.required' => 'Status wajib diisi.',
            'status.in' => 'Status harus tersedia, digunakan, atau perbaikan.',
            'id_pelanggan.required' => 'ID pelanggan wajib diisi.',
            'id_pelanggan.unique' => 'Pelanggan ini sudah memiliki perangkat.',
        ];
    }
}
