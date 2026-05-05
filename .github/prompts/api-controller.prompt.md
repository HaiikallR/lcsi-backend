---
mode: agent
description: Membuat API Controller baru untuk resource tertentu sesuai konvensi proyek LCSI Backend
---

Buatkan sebuah **API Resource Controller** untuk model `${input:namaModel}` mengikuti konvensi proyek ini.

## Yang Harus Dibuat

1. **Controller** → `app/Http/Controllers/Api/${input:namaModel}Controller.php`
2. **FormRequest Store** → `app/Http/Requests/Store${input:namaModel}Request.php`
3. **FormRequest Update** → `app/Http/Requests/Update${input:namaModel}Request.php`
4. **API Resource** → `app/Http/Resources/${input:namaModel}Resource.php`
5. **Resource Collection** → `app/Http/Resources/${input:namaModel}Collection.php`
6. **Route** → tambahkan di `routes/api.php`
7. **Feature Test** → `tests/Feature/${input:namaModel}ControllerTest.php`

---

## Aturan Controller

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store${input:namaModel}Request;
use App\Http\Requests\Update${input:namaModel}Request;
use App\Http\Resources\${input:namaModel}Collection;
use App\Http\Resources\${input:namaModel}Resource;
use App\Models\${input:namaModel};
use Illuminate\Http\JsonResponse;

class ${input:namaModel}Controller extends Controller
{
    public function index(): JsonResponse
    {
        $data = ${input:namaModel}::orderByDesc('id')->paginate(10);
        return response()->json(new ${input:namaModel}Collection($data));
    }

    public function store(Store${input:namaModel}Request $request): JsonResponse
    {
        $item = ${input:namaModel}::create($request->validated());
        return response()->json(new ${input:namaModel}Resource($item), 201);
    }

    public function show(${input:namaModel} $${input:namaModelKecil}): JsonResponse
    {
        return response()->json(new ${input:namaModel}Resource($${input:namaModelKecil}));
    }

    public function update(Update${input:namaModel}Request $request, ${input:namaModel} $${input:namaModelKecil}): JsonResponse
    {
        $${input:namaModelKecil}->update($request->validated());
        return response()->json(new ${input:namaModel}Resource($${input:namaModelKecil}));
    }

    public function destroy(${input:namaModel} $${input:namaModelKecil}): JsonResponse
    {
        $${input:namaModelKecil}->delete();
        return response()->json(null, 204);
    }
}
```

---

## Aturan Tambahan

- `authorize()` pada FormRequest harus return `true`
- Jangan return array mentah — selalu pakai `XxxResource` atau `XxxCollection`
- Jangan gunakan `$request->all()` — pakai `$request->validated()`
- Tambahkan method `messages()` di FormRequest dengan pesan validasi **Bahasa Indonesia**
- Route didaftarkan di dalam `Route::middleware('auth:sanctum')->group(...)` di `routes/api.php`
- Test menggunakan `Sanctum::actingAs()` dan `RefreshDatabase`
- Field sensitif (`password`, `remember_token`) tidak boleh ada di Resource

---

## Referensi Field Model

Sesuaikan field `$fillable`, `$hidden`, dan `$casts` berdasarkan kebutuhan model `${input:namaModel}`.  
Gunakan nama field dalam **Bahasa Indonesia** jika tidak ada konvensi teknis yang mengharuskan nama tertentu.
