# Blueprint Prompting Backend API Laravel 13

## 1. Tujuan
Dokumen ini adalah panduan prompt modular untuk membangun API Laravel 13 yang:
- mengikuti dokumentasi resmi Laravel 13.x,
- menjaga best practice,
- sederhana (tidak over-engineering),
- clean code,
- separation of concerns,
- dan struktur direktori yang konsisten.

Dokumen ini dipakai saat kamu mengirim file skeleton (controller, model, request, collection/resource, factory, migration, seeder, test), lalu AI mengimplementasikan kodenya bertahap.

---

## 2. Deep Dive Workspace (Ringkasan Kondisi Saat Ini)

### Temuan aktual
- API Controller sudah ada, tetapi method CRUD utama masih kosong (store, show, update, destroy).
- Route API masih minimal.
- FormRequest masih skeleton (authorize false, rules kosong).
- Resource collection masih sangat dasar.
- Policy sudah ada tetapi semua aturan saat ini false.
- Sanctum sudah tersedia dan konfigurasi dasar ada.

### Implikasi
- Fondasi struktur sudah benar, tetapi implementasi behavior API belum selesai.
- Fokus implementasi harus pada: validasi, otorisasi, response resource, route API, dan feature test.

---

## 3. Deep Scan Workspace (Target File yang Akan Sering Dipakai)

### Layer HTTP
- app/Http/Controllers/Api
- app/Http/Requests
- app/Http/Resources
- routes/api.php

### Domain dan data
- app/Models
- database/migrations
- database/factories
- database/seeders

### Security
- app/Policies
- config/auth.php
- config/sanctum.php

### Quality gate
- tests/Feature

---

## 4. Deep Search Docs Laravel 13.x (Sumber Resmi)
Gunakan ini sebagai referensi utama saat coding:
- https://laravel.com/docs/13.x/controllers
- https://laravel.com/docs/13.x/routing
- https://laravel.com/docs/13.x/validation
- https://laravel.com/docs/13.x/eloquent-resources
- https://laravel.com/docs/13.x/sanctum
- https://laravel.com/docs/13.x/http-tests
- https://laravel.com/docs/13.x/structure

Aturan prioritas:
1. Dokumentasi resmi Laravel 13.x
2. Konvensi kode di workspace ini
3. Sederhana dan maintainable

---

## 5. Cara AI Membuatnya (Workflow Saat Kamu Kirim File)

### Alur kerja wajib
1. AI membaca file yang kamu kirim + file terkait (route, model, request, resource, test).
2. AI menganalisis kontrak data (field, nullable, unique, casting).
3. AI menulis implementasi sesuai scope file yang diminta.
4. AI menjaga separation of concerns:
   - validasi di FormRequest,
   - business flow di Controller (ringkas),
   - transform output di Resource,
   - authorization di Policy / authorize,
   - contract endpoint di routes/api.php,
   - verifikasi behavior di Feature Test.
5. AI review hasil terhadap docs Laravel 13.x.
6. AI jalankan test relevan bila memungkinkan.

### Prinsip implementasi
- Tidak menambah abstraction baru jika tidak ada kebutuhan nyata.
- Hindari service layer/repository tambahan bila CRUD sederhana sudah cukup dengan Laravel native pattern.
- Gunakan Route Model Binding.
- Gunakan FormRequest untuk semua endpoint write (store/update).
- Gunakan Resource/ResourceCollection untuk response API.
- Pastikan status code tepat (200/201/204/404/422/403).

---

## 6. Prompting Pack Modular (Siap Pakai)

## 6.1 Prompt Global (Kirim dulu sebelum prompt file)
```text
Kamu adalah senior Laravel API engineer untuk Laravel 13.
Gunakan dokumentasi resmi Laravel 13.x sebagai acuan utama.
Prinsip wajib: best practice, simplify, tidak over-engineering, clean code, separation of concerns, dan struktur direktori yang rapi.

Aturan coding:
1) Validasi harus di FormRequest.
2) Otorisasi harus via policy / authorize method.
3) Response API harus lewat JsonResource/ResourceCollection.
4) Controller harus tipis dan fokus orchestration.
5) Hindari duplikasi dan magic string berulang.
6) Tulis kode yang mudah dites.
7) Jangan ubah scope file di luar yang diminta kecuali benar-benar diperlukan, dan jelaskan alasannya.

Sebelum menulis kode:
- Baca file terkait yang relevan.
- Jelaskan singkat rencana perubahan.

Setelah menulis kode:
- Berikan ringkasan perubahan.
- Sebutkan potensi risiko.
- Berikan checklist verifikasi.
```

## 6.2 Prompt Model
```text
Implementasikan model ini untuk kebutuhan API CRUD sesuai Laravel 13.
Fokus:
- fillable/guarded yang aman,
- casts yang relevan,
- relasi jika dibutuhkan,
- trait yang diperlukan (HasFactory, HasApiTokens jika autentikasi token).

Jangan tambahkan logic berat di model jika belum perlu.
```

## 6.3 Prompt Migration
```text
Implementasikan migration ini sesuai kebutuhan endpoint API.
Fokus:
- tipe kolom tepat,
- index dan unique yang relevan,
- foreign key bila ada relasi,
- down() harus konsisten dengan up().

Jaga agar skema sederhana dan cukup untuk use case saat ini.
```

## 6.4 Prompt FormRequest (Store/Update)
```text
Implementasikan FormRequest ini dengan best practice Laravel 13.
Fokus:
- authorize() sesuai policy/role requirement,
- rules() lengkap dan aman,
- unique rule update harus ignore record saat ini,
- field optional harus nullable/sometimes sesuai kebutuhan.

Tambahkan messages()/attributes() hanya jika benar-benar diperlukan.
```

## 6.5 Prompt Resource dan Collection
```text
Implementasikan JsonResource/ResourceCollection untuk output API yang konsisten.
Fokus:
- mapping field eksplisit,
- hindari expose data sensitif,
- support pagination output jika collection paginated,
- tetap simpel tanpa format berlebihan.
```

## 6.6 Prompt Controller API
```text
Implementasikan controller API ini untuk endpoint index, store, show, update, destroy.
Fokus:
- gunakan FormRequest pada store/update,
- gunakan Route Model Binding,
- gunakan Policy authorize bila perlu,
- return Resource/Collection,
- status code tepat:
  - index/show/update: 200,
  - store: 201,
  - destroy: 204.

Controller harus tipis, tidak ada query/logic yang seharusnya di tempat lain.
```

## 6.7 Prompt Route API
```text
Refactor routes/api.php sesuai endpoint resource API.
Fokus:
- gunakan Route::apiResource jika cocok,
- middleware auth:sanctum untuk endpoint protected,
- naming jelas bila dibutuhkan,
- hindari route duplikat.
```

## 6.8 Prompt Factory dan Seeder
```text
Implementasikan factory dan seeder untuk data uji API.
Fokus:
- data realistis,
- aman untuk environment development/testing,
- tidak membuat coupling yang tidak perlu.
```

## 6.9 Prompt Feature Test API
```text
Buat/implementasikan feature test API untuk CRUD endpoint ini.
Minimal test:
- index sukses,
- store sukses,
- store gagal validasi (422),
- show not found (404),
- update sukses,
- delete sukses (204),
- unauthorized/forbidden flow bila endpoint protected.

Gunakan helper test JSON Laravel dan assert status + struktur response penting.
```

## 6.10 Prompt Review dan Hardening
```text
Lakukan review implementasi endpoint ini dengan fokus:
- bug fungsional,
- regresi behavior,
- celah validasi,
- celah otorisasi,
- konsistensi response API,
- gap test.

Jika ada issue, berikan prioritas: critical, high, medium, low.
```

---

## 7. Tahapan Eksekusi (Sesuai Permintaan)

### Tahap 1: Deep Dive Workspace
- Identifikasi pola coding yang sudah ada.
- Identifikasi skeleton yang belum terimplementasi.

### Tahap 2: Deep Scan Workspace
- Petakan dependency antar file (Controller -> Request -> Resource -> Model -> Routes -> Tests).

### Tahap 3: Deep Search Internet Laravel 13
- Validasi pola implementasi ke docs resmi.

### Tahap 4: Deep Dive API Laravel 13
- Pastikan resource routing, form request, api resources, sanctum, dan tests sesuai panduan.

### Tahap 5: Fetch Dokumentasi yang Dibutuhkan
- Ambil detail aturan spesifik sesuai file yang sedang dikerjakan (misalnya unique ignore untuk update, token abilities, pagination resource).

### Tahap 6: Deep Analyst
- Review desain endpoint, quality gate, lalu implementasi bertahap.

---

## 8. Quality Gate Wajib Sebelum Selesai
Checklist ini harus lolos:
- Validasi lengkap dan benar.
- Otorisasi tidak longgar.
- Tidak ada expose field sensitif.
- Response API konsisten.
- Status code benar.
- Route bersih dan tidak duplikat.
- Test minimal CRUD + validasi + auth flow.
- Tidak ada over-engineering.

---

## 9. Optional .github (Rekomendasi, Fokus Backend API)
Bagian ini opsional. Jika ingin menstandarkan kolaborasi AI di repo:

### Struktur yang direkomendasikan
- .github/copilot-instructions.md
- .github/prompts/api-controller.prompt.md
- .github/prompts/api-request.prompt.md
- .github/prompts/api-resource.prompt.md
- .github/prompts/api-test.prompt.md
- .github/agents/backend-api.agent.md

### Isi minimal
- copilot-instructions.md:
  - standar coding Laravel API,
  - aturan validasi/otorisasi/resource,
  - larangan over-engineering.
- prompts/*.prompt.md:
  - prompt siap pakai per jenis file.
- agents/backend-api.agent.md:
  - mode AI khusus backend API Laravel.

Catatan: karena ini optional, bisa diterapkan setelah endpoint utama berjalan stabil.

---

## 10. Definition of Done
Implementasi dianggap selesai jika:
1. Endpoint CRUD jalan sesuai kontrak.
2. Validasi + otorisasi sesuai docs.
3. Response API konsisten via Resource.
4. Feature test utama lulus.
5. Kode tetap sederhana, bersih, dan maintainable.

---

## 11. Cara Pakai Dokumen Ini Sekarang
Saat mulai implementasi per entity:
1. Kirim Prompt Global.
2. Kirim file skeleton (model, migration, request, resource, controller, route, test).
3. Jalankan prompt modular sesuai urutan:
   - migration -> model -> request -> resource -> controller -> route -> test -> review.
4. Lakukan iterasi sampai quality gate lolos.

Dengan alur ini, AI akan bekerja konsisten, cepat, dan tetap aman untuk skala backend API Laravel 13.
