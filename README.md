# Katekese LMS

Sistem manajemen pembelajaran katekese berbasis web untuk Paroki Maria Marganingsih Kalasan, dibangun dengan Laravel 12 dan MySQL.

Aplikasi ini bukan LMS online murni — katekese tetap diajarkan lewat pertemuan tatap muka, dan sisi online (materi, tugas, test, presensi) berfungsi sebagai pendamping sekaligus pengganti proses berbasis kertas (presensi manual, raport cetak, dsb).

## Fitur Utama

**Katekis (pengelola kelas)**
- Kelola Program Katekese (Baptis, Komuni, Krisma) dan Kelas/Angkatan per program
- Materi pembelajaran — upload file (PDF, dokumen, gambar) atau tautan video (YouTube/Vimeo)
- Tugas dengan pengumpulan dan penilaian
- Test/kuis dengan bank soal dan penilaian otomatis
- Presensi tatap muka (jadwal pertemuan + rekap kehadiran, dengan self check-in siswa yang bisa dikoreksi katekis)
- Penilaian per materi (A/B/C) dan keputusan kelulusan per kelas
- Manajemen data Siswa & Katekis (profil, aktif/nonaktif, reset password)
- Verifikasi & moderasi pendaftaran siswa ke kelas (approve/reject/transfer)
- Dashboard dengan grafik (peserta per angkatan, kelulusan, katekis per bidang)

**Peserta (siswa)**
- Registrasi mandiri (dengan verifikasi email) dan pendaftaran ke kelas
- Akses materi, pengerjaan tugas & test, lihat nilai
- Presensi mandiri (check-in) saat jadwal pertemuan dibuka

## Persyaratan

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL/MariaDB
- Ekstensi PHP: `pdo_mysql`, `mbstring`, `xml`, `curl`, `zip`

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

Sesuaikan variabel berikut di `.env`:

- **Database** — `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` (default `DB_CONNECTION=mysql`, buat database kosong dulu di MySQL sebelum migrate).
- **Mail** — `MAIL_USERNAME`/`MAIL_PASSWORD` (default pakai SMTP relay Brevo) diperlukan agar link verifikasi email & notifikasi lain terkirim. Untuk development lokal bisa diganti `MAIL_MAILER=log` supaya email cukup ditulis ke log.
- **Storage** — default `FILESYSTEM_DISK=local` (file materi/tugas disimpan lokal). Untuk produksi tersedia disk `b2` (Backblaze B2, kompatibel S3) — isi `B2_KEY_ID`, `B2_APPLICATION_KEY`, `B2_BUCKET`, `B2_REGION`, `B2_ENDPOINT` lalu set `FILESYSTEM_DISK=b2`.

### 4. Setup database

```bash
php artisan migrate
php artisan db:seed
```

Perintah `migrate` akan membuat semua tabel secara otomatis. `db:seed` mengisi data awal (admin, program, dan beberapa katekis contoh).

### 5. Build asset frontend

```bash
npm run build
```

### 6. Jalankan aplikasi

```bash
composer run dev
```

Perintah ini menjalankan server, queue listener, log watcher (`pail`), dan Vite dev server sekaligus. Atau jika hanya butuh server:

```bash
php artisan serve
```

Akses di browser: `http://localhost:8000`

---

## Akun Default

Setelah `db:seed`, akun admin (katekis) tersedia:

| Field    | Value                     |
|----------|---------------------------|
| Email    | admin@gerejakalasan.org   |
| Password | admin123                  |

> Ganti password ini segera setelah login pertama, terutama sebelum deploy ke produksi.

---

## Shortcut (satu perintah)

Jika tidak ingin langkah manual, jalankan:

```bash
composer run setup
```

Script ini otomatis menjalankan `composer install`, copy `.env`, generate key, migrate, `npm install`, dan build asset. Pastikan koneksi database di `.env` sudah benar sebelum menjalankan ini. Setelah itu tinggal jalankan `php artisan db:seed` lalu `composer run dev`.
