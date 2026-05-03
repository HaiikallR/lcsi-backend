<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePengeluaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_tiket' => ['required', 'exists:tikets,id', Rule::unique('pengeluarans', 'id_tiket')->ignore($this->route('pengeluaran'))],
            'id_teknisi' => ['required', 'exists:teknisis,id'],
            'judul' => ['required', 'string', 'max:255'],
            'jumlah' => ['required', 'integer', 'min:0'],
            'kategori' => ['required', 'string', 'max:100'],
            'bulan' => ['required', 'string', 'max:20'],
            'tahun' => ['required', 'digits:4'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_tiket.required' => 'Tiket wajib dipilih.',
            'id_tiket.unique' => 'Tiket ini sudah memiliki data pengeluaran.',
            'id_teknisi.required' => 'Teknisi wajib dipilih.',
            'judul.required' => 'Judul pengeluaran wajib diisi.',
            'jumlah.required' => 'Jumlah pengeluaran wajib diisi.',
        ];
    }
}
