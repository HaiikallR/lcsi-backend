<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoretagihanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_pelanggan' => ['required', 'integer', 'exists:pelanggans,id'],
            'jumlah' => ['required', 'integer', 'min:0'],
            'bulan' => ['required', 'string', 'max:20'],
            'tahun' => ['required', 'digits:4'],
            'catatan' => ['nullable', 'string'],
            'status' => ['required', 'in:belum bayar,menunggu,sudah bayar'],
            'tanggal_bayar' => ['nullable', 'date'],
            'tanggal_verifikasi' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_pelanggan.required' => 'Pelanggan wajib dipilih.',
            'id_pelanggan.exists' => 'Pelanggan tidak ditemukan.',
            'jumlah.required' => 'Jumlah tagihan wajib diisi.',
            'jumlah.integer' => 'Jumlah tagihan harus berupa angka.',
            'bulan.required' => 'Bulan wajib diisi.',
            'tahun.required' => 'Tahun wajib diisi.',
            'tahun.digits' => 'Tahun harus 4 digit.',
            'status.in' => 'Status harus belum bayar, menunggu, atau sudah bayar.',
            'tanggal_bayar.date' => 'Tanggal bayar harus format tanggal valid.',
            'tanggal_verifikasi.date' => 'Tanggal verifikasi harus format tanggal valid.',
        ];
    }
}
