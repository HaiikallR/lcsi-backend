---
mode: agent
description: Membuat StoreXxxRequest dan UpdateXxxRequest untuk validasi input API
---

Buatkan **FormRequest** untuk resource `${input:namaModel}` sesuai konvensi proyek ini.

## Yang Harus Dibuat

1. `app/Http/Requests/Store${input:namaModel}Request.php`
2. `app/Http/Requests/Update${input:namaModel}Request.php`

---

## Template Store Request

```php
<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Store${input:namaModel}Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Isi rules validasi sesuai field model
            // Contoh:
            // 'nama'  => ['required', 'string', 'max:255'],
            // 'email' => ['required', 'email', 'unique:${input:namaTable}'],
        ];
    }

    public function messages(): array
    {
        return [
            // Isi pesan validasi dalam Bahasa Indonesia
            // Contoh:
            // 'nama.required'  => 'Nama wajib diisi.',
            // 'email.required' => 'Email wajib diisi.',
            // 'email.email'    => 'Format email tidak valid.',
            // 'email.unique'   => 'Email sudah terdaftar.',
        ];
    }
}
```

---

## Template Update Request

```php
<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Update${input:namaModel}Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Sama seperti Store tapi email unique harus ignore ID saat ini:
            // 'nama'  => ['required', 'string', 'max:255'],
            // 'email' => ['required', 'email',
            //     Rule::unique('${input:namaTable}')->ignore($this->route('${input:namaModelKecil}'))
            // ],
        ];
    }

    public function messages(): array
    {
        return [
            // Pesan validasi Bahasa Indonesia
        ];
    }
}
```

---

## Aturan Penting

- `authorize()` **wajib** return `true` — jangan biarkan `false`
- Gunakan `Rule::unique(...)->ignore(...)` untuk update agar tidak konflik dengan data sendiri
- Gunakan `$this->validated()` di controller, **bukan** `$request->all()`
- Semua pesan validasi dalam `messages()` menggunakan **Bahasa Indonesia**
- Field `password` jika ada: gunakan rule `['required','string','min:8','confirmed']` untuk store,
  dan `['nullable','string','min:8','confirmed']` untuk update (opsional saat update)
- Jangan validasi field yang tidak ada di `$fillable` model
