<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTiketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jenis_pekerjaan' => ['required', Rule::in(['pasang baru', 'perbaikan/gangguan', 'relokasi', 'maintenance'])],
            'id_pelanggan' => [Rule::requiredIf($this->input('jenis_pekerjaan') !== 'pasang baru'), 'nullable', 'exists:pelanggans,id'],
            'calon_pelanggan_nama' => [Rule::requiredIf($this->input('jenis_pekerjaan') === 'pasang baru'), 'nullable', 'string', 'max:255'],
            'calon_pelanggan_no_hp' => [Rule::requiredIf($this->input('jenis_pekerjaan') === 'pasang baru'), 'nullable', 'string', 'max:25'],
            'calon_pelanggan_alamat' => [Rule::requiredIf($this->input('jenis_pekerjaan') === 'pasang baru'), 'nullable', 'string'],
            'id_teknisi' => ['required', 'exists:teknisis,id'],
            'ongkos_teknisi' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'jenis_pekerjaan.required' => 'Jenis pekerjaan wajib diisi.',
            'jenis_pekerjaan.in' => 'Jenis pekerjaan tidak valid.',
            'id_pelanggan.required' => 'Pelanggan wajib dipilih untuk tiket selain pasang baru.',
            'id_teknisi.required' => 'Teknisi wajib dipilih.',
            'ongkos_teknisi.required' => 'Perkiraan ongkos teknisi wajib diisi.',
            'calon_pelanggan_nama.required' => 'Nama calon pelanggan wajib diisi untuk pasang baru.',
            'calon_pelanggan_no_hp.required' => 'Nomor HP calon pelanggan wajib diisi untuk pasang baru.',
            'calon_pelanggan_alamat.required' => 'Alamat calon pelanggan wajib diisi untuk pasang baru.',
        ];
    }
}
