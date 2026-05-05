# Copilot Instructions — LCSI Backend API (Laravel 13.x)

Proyek ini adalah **REST API** yang dibangun dengan **Laravel 13.x** dan autentikasi menggunakan **Laravel Sanctum**.  
Fokus pekerjaan: backend API saja — tidak ada Blade view, tidak ada frontend.

---

## Konvensi Umum

- **Bahasa variabel & komentar**: Bahasa Indonesia (kecuali nama class/method yang mengikuti Laravel convention)
- **PHP version**: 8.2+
- **Strict types**: tambahkan `declare(strict_types=1);` di setiap file PHP baru
- **Response format**: selalu gunakan `ApiResource` / `ResourceCollection` — jangan return array mentah
- **Status code**: gunakan HTTP status code yang tepat (`200`, `201`, `204`, `422`, `401`, `403`, `404`)

---

## Struktur Direktori

```
app/
  Http/
    Controllers/Api/   ← semua controller API di sini
    Requests/          ← StoreXxxRequest, UpdateXxxRequest
    Resources/         ← XxxResource (single), XxxCollection (list)
  Models/              ← Eloquent models
  Policies/            ← Authorization policies
routes/
  api.php              ← semua route API
database/
  migrations/
  factories/
  seeders/
tests/
  Feature/             ← HTTP/integration tests
  Unit/                ← unit tests
```

---

## Model

- Field utama Admin: `nama`, `email`, `password`
- Hidden fields: `password`, `remember_token`
- Cast: `email_verifikasi` → `datetime`, `password` → `hashed`
- Traits: `HasFactory`, `Notifiable`, `HasApiTokens` (Sanctum)
- Selalu tentukan `$fillable` secara eksplisit — **jangan** gunakan `$guarded = []`

---

## Controller API

- Semua controller API extends `App\Http\Controllers\Controller`
- Simpan di `app/Http/Controllers/Api/`
- Gunakan **Resource Controller** dengan `apiResource` route (tanpa `create` dan `edit`)
- Method yang diperlukan: `index`, `store`, `show`, `update`, `destroy`
- **Jangan** taruh logika bisnis di controller — delegasikan ke Service atau langsung ke Model jika sederhana
- Selalu inject `FormRequest` untuk `store` dan `update`

Contoh skeleton:
```php
public function store(StoreAdminRequest $request): JsonResponse
{
    $admin = Admin::create($request->validated());
    return response()->json(new AdminResource($admin), 201);
}

public function destroy(Admin $admin): JsonResponse
{
    $admin->delete();
    return response()->json(null, 204);
}
```

---

## FormRequest

- `authorize()` harus return `true` (atau logika policy yang sesuai) — **jangan biarkan `false`**
- Gunakan `$this->validated()` di controller, **bukan** `$request->all()`
- Tambahkan `messages()` untuk pesan validasi dalam Bahasa Indonesia
- Rule `unique` untuk email harus mengabaikan record saat ini saat update:
  ```php
  'email' => ['required','email', Rule::unique('admins')->ignore($this->route('admin'))],
  ```

---

## API Resource

- Gunakan `XxxResource` untuk single item, `XxxCollection` untuk list/paginate
- `XxxCollection` extend `ResourceCollection` dan gunakan `XxxResource::class` sebagai `$collects`
- Sertakan `$this->when()` untuk field kondisional (misal: token hanya saat login)
- **Jangan** expose field sensitif (`password`, `remember_token`)

Contoh:
```php
// AdminResource
public function toArray(Request $request): array
{
    return [
        'id'    => $this->id,
        'nama'  => $this->nama,
        'email' => $this->email,
        'dibuat_pada' => $this->created_at?->toDateTimeString(),
    ];
}
```

---

## Routes API

- Semua route di `routes/api.php`
- Gunakan `Route::apiResource()` untuk CRUD lengkap
- Grup route yang butuh autentikasi dengan `middleware('auth:sanctum')`
- Prefix versioning jika perlu: `Route::prefix('v1')->group(...)`

```php
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('admin', AdminController::class);
});
```

---

## Autentikasi (Sanctum)

- Gunakan `auth:sanctum` middleware untuk semua endpoint yang memerlukan autentikasi
- Model Admin harus menggunakan trait `HasApiTokens`
- Buat token dengan `$admin->createToken('nama-token')->plainTextToken`
- Revoke token dengan `$request->user()->currentAccessToken()->delete()`
- Guard Sanctum ada di `config/sanctum.php` → `stateful` dan `guard`

---

## Policy & Authorization

- Buat Policy per model: `AdminPolicy`
- Register di `AppServiceProvider` atau gunakan auto-discovery
- Gunakan `$this->authorize()` di controller atau `can:` middleware di route
- **Jangan biarkan semua method Policy return `false`** — implementasikan logika yang benar

---

## Testing

- Semua test HTTP ada di `tests/Feature/`
- Gunakan `Sanctum::actingAs($admin)` untuk autentikasi dalam test
- Gunakan `RefreshDatabase` trait di setiap test class
- Test minimal per endpoint: **happy path** + **validasi gagal** + **unauthorized**

```php
use Laravel\Sanctum\Sanctum;

public function test_admin_dapat_melihat_list_admin(): void
{
    Sanctum::actingAs(Admin::factory()->create());
    $response = $this->getJson('/api/admin');
    $response->assertStatus(200)->assertJsonStructure(['data']);
}
```

---

## Hal yang TIDAK Boleh Dilakukan

- ❌ Jangan return `response()->json($model)` langsung — wajib lewat Resource
- ❌ Jangan gunakan `$request->all()` atau `$request->input()` untuk mass-assign
- ❌ Jangan buat route di `web.php` untuk endpoint API
- ❌ Jangan hardcode credential/secret di kode — gunakan `.env`
- ❌ Jangan disable CSRF kecuali sudah dikecualikan via Sanctum stateful config
- ❌ Jangan tambahkan logika di Model yang seharusnya di Controller/Service
