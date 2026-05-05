---
mode: agent
description: Membuat XxxResource (single) dan XxxCollection (list/paginate) untuk API response
---

Buatkan **API Resource dan Collection** untuk model `${input:namaModel}` sesuai konvensi proyek ini.

## Yang Harus Dibuat

1. `app/Http/Resources/${input:namaModel}Resource.php` — untuk single item
2. `app/Http/Resources/${input:namaModel}Collection.php` — untuk list/paginate

---

## Template Resource (Single Item)

```php
<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ${input:namaModel}Resource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            // Tambahkan field sesuai model — gunakan nama field Bahasa Indonesia jika ada
            // 'nama'        => $this->nama,
            // 'email'       => $this->email,
            'dibuat_pada' => $this->created_at?->toDateTimeString(),
            'diubah_pada' => $this->updated_at?->toDateTimeString(),

            // Field kondisional — hanya muncul saat kondisi terpenuhi:
            // 'token' => $this->when(isset($this->token), $this->token ?? null),
        ];
    }
}
```

---

## Template Collection (List/Paginate)

```php
<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ${input:namaModel}Collection extends ResourceCollection
{
    public string $collects = ${input:namaModel}Resource::class;

    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
        ];
    }
}
```

---

## Aturan Penting

- `XxxResource` extends `JsonResource` — untuk single item (`show`, `store`, `update`)
- `XxxCollection` extends `ResourceCollection` — untuk list (`index` dengan paginate)
- `$collects` pada Collection **wajib** di-set ke `XxxResource::class`
- **Jangan** expose field sensitif: `password`, `remember_token`
- Gunakan `$this->when(kondisi, nilai)` untuk field kondisional seperti token auth
- Format tanggal menggunakan `->toDateTimeString()` atau `->format('Y-m-d H:i:s')`
- Key response menggunakan Bahasa Indonesia jika memungkinkan (`dibuat_pada`, `diubah_pada`, dll.)
- Jika ada relasi, gunakan `$this->whenLoaded('namaRelasi', fn() => NamaResource::collection($this->namaRelasi))`

---

## Contoh Penggunaan di Controller

```php
// Single item
return response()->json(new ${input:namaModel}Resource($item), 201);

// List/Paginate
return response()->json(new ${input:namaModel}Collection(${input:namaModel}::paginate(10)));
```
