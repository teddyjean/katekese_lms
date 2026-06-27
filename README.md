# Katekese LMS

Sistem manajemen pembelajaran katekese berbasis web, dibangun dengan Laravel 12 dan SQLite.

## Persyaratan

- PHP >= 8.2
- Composer
- Node.js & NPM
- Ekstensi PHP: `pdo_sqlite`, `sqlite3`, `mbstring`, `xml`, `curl`, `zip`

## Instalasi

### 1. Clone repository

```bash
git clone https://github.com/teddyjean/katekese_lms.git
cd katekese_lms
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Konfigurasi environment

```bash
cp .env.example .env
php artisan key:generate
```

> File `.env` tidak perlu diubah apa-apa — project sudah dikonfigurasi pakai SQLite secara default.

### 4. Setup database

```bash
touch database/database.sqlite   # Linux/Mac
# Windows (PowerShell):
New-Item database/database.sqlite -ItemType File

php artisan migrate
php artisan db:seed
```

Perintah `migrate` akan membuat semua tabel secara otomatis. `db:seed` mengisi data awal (admin dan program).

### 5. Build asset frontend

```bash
npm run build
```

### 6. Jalankan aplikasi

```bash
composer run dev
```

Atau jika hanya butuh server tanpa queue/log watcher:

```bash
php artisan serve
```

Akses di browser: `http://localhost:8000`

---

## Akun Default

Setelah `db:seed`, akun admin tersedia:

| Field    | Value                     |
|----------|---------------------------|
| Email    | admin@gerejakalasan.org   |
| Password | admin123                  |

> Ganti password setelah login pertama.

---

## Shortcut (satu perintah)

Jika tidak ingin langkah manual, jalankan:

```bash
composer run setup
```

Script ini otomatis menjalankan `composer install`, copy `.env`, generate key, migrate, `npm install`, dan build asset. Setelah itu tinggal jalankan `php artisan db:seed` lalu `composer run dev`.
