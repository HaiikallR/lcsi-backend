<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class Updatepermintaan_upgradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_pelanggan' => ['required', 'integer', 'exists:pelanggans,id'],
            'paket_lama' => ['required', 'string', 'max:100'],
            'paket_baru' => ['required', 'string', 'max:100'],
            'harga_baru' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:menunggu,disetujui,ditolak'],
            'disetujui_pada' => ['nullable', 'date', 'required_if:status,disetujui'],
            'ditolak_pada' => ['nullable', 'date', 'required_if:status,ditolak'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_pelanggan.required' => 'Pelanggan wajib dipilih.',
            'id_pelanggan.exists' => 'Pelanggan tidak ditemukan.',
            'paket_lama.required' => 'Paket lama wajib diisi.',
            'paket_baru.required' => 'Paket baru wajib diisi.',
            'harga_baru.required' => 'Harga baru wajib diisi.',
            'harga_baru.numeric' => 'Harga baru harus berupa angka.',
            'status.in' => 'Status harus menunggu, disetujui, atau ditolak.',
            'disetujui_pada.required_if' => 'Tanggal persetujuan wajib diisi saat status disetujui.',
            'ditolak_pada.required_if' => 'Tanggal penolakan wajib diisi saat status ditolak.',
        ];
    }
}
