<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorepemasukanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_pelanggan' => ['required', 'integer', 'exists:pelanggans,id'],
            'jenis_pemasukan' => ['required', 'string', 'max:100'],
            'jumlah_bayar' => ['required', 'integer', 'min:0'],
            'metode_bayar' => ['required', 'string', 'max:50'],
            'bukti_bayar' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
            'status' => ['required', 'in:lunas,menunggu'],
            'bulan_tagihan' => ['required', 'string', 'max:20'],
            'tahun_tagihan' => ['required', 'digits:4'],
            'tanggal_bayar' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_pelanggan.required' => 'Pelanggan wajib dipilih.',
            'id_pelanggan.exists' => 'Pelanggan tidak ditemukan.',
            'jenis_pemasukan.required' => 'Jenis pemasukan wajib diisi.',
            'jumlah_bayar.required' => 'Jumlah bayar wajib diisi.',
            'jumlah_bayar.integer' => 'Jumlah bayar harus berupa angka.',
            'metode_bayar.required' => 'Metode bayar wajib diisi.',
            'status.in' => 'Status harus lunas atau menunggu.',
            'bulan_tagihan.required' => 'Bulan tagihan wajib diisi.',
            'tahun_tagihan.required' => 'Tahun tagihan wajib diisi.',
            'tahun_tagihan.digits' => 'Tahun tagihan harus 4 digit.',
            'tanggal_bayar.date' => 'Tanggal bayar harus format tanggal yang valid.',
        ];
    }
}
