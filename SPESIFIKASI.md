# Spesifikasi Project — Katekese LMS (SMPSI)

## Gambaran Umum

**SMPSI** adalah sistem Learning Management System (LMS) berbasis web untuk kegiatan katekese di **Paroki Maria Marganingsih Kalasan**. Sistem ini mengelola program katekese, kelas (batch/angkatan), materi, tugas, ujian, dan nilai peserta.

---

## Teknologi yang Digunakan

### Backend

| Teknologi | Versi | Keterangan |
|---|---|---|
| PHP | ^8.2 | Bahasa pemrograman utama |
| Laravel | ^12.0 | Framework PHP utama |
| Laravel Tinker | ^2.10 | REPL untuk debugging via artisan |

### Frontend

| Teknologi | Versi | Keterangan |
|---|---|---|
| Tailwind CSS | ^4.3 | Utility-first CSS framework |
| Vite | ^7.0 | Build tool & dev server |
| Laravel Vite Plugin | ^2.0 | Integrasi Vite dengan Laravel |
| Axios | ^1.11 | HTTP client untuk JavaScript |

### Database

| Teknologi | Keterangan |
|---|---|
| SQLite | Default untuk development (file: `database/database.sqlite`) |
| MySQL/PostgreSQL | Didukung melalui konfigurasi `.env` |

### Dev Tools

| Teknologi | Keterangan |
|---|---|
| PHPUnit ^11.5 | Unit & feature testing |
| Laravel Pint ^1.24 | PHP code style fixer |
| Laravel Sail ^1.41 | Docker development environment |
| Laravel Pail ^1.2 | Real-time log viewer via terminal |
| Faker PHP ^1.23 | Data dummy untuk seeder/factory |
| Mockery ^1.6 | Mocking library untuk testing |

### Template Engine

- **Blade** — template engine bawaan Laravel, digunakan untuk semua halaman

---

## Arsitektur Aplikasi

Aplikasi mengikuti pola **MVC (Model-View-Controller)** standar Laravel:

- **Model** — Eloquent ORM, ada di `app/Models/`
- **View** — Blade templates, ada di `resources/views/`
- **Controller** — Ada di `app/Http/Controllers/`, diorganisir per role

### Sistem Autentikasi & Otorisasi

- Autentikasi custom (tanpa Breeze/Sanctum) menggunakan `Auth` facade Laravel
- Otorisasi berbasis **role** melalui `RoleMiddleware` (`app/Http/Middleware/RoleMiddleware.php`)
- Dua role utama:
  - `katekis` — admin/pengajar, akses ke panel `/admin`
  - `peserta` — siswa/peserta didik, akses ke panel `/peserta`

---

## Fitur Utama per Role

### Katekis (Admin/Pengajar)
- Manajemen pengguna (tambah, edit, aktifkan/nonaktifkan, reset password)
- Manajemen program katekese
- Manajemen batch/angkatan (assign katekis & peserta ke kelas)
- Upload materi belajar
- Buat dan kelola tugas + penilaian submission
- Buat dan kelola soal ujian (test) beserta pertanyaan pilihan ganda

### Peserta (Siswa)
- Daftar/enroll ke kelas
- Lihat dan unduh materi belajar
- Submit tugas
- Ikuti ujian (test) dengan batas waktu
- Lihat nilai (tugas + test)

---

## Struktur Database (Model Utama)

```
users               — pengguna (role: katekis | peserta)
programs            — program katekese
batches             — angkatan/kelas per program
batch_katekis       — pivot: katekis yang mengajar di batch
batch_participants  — pivot: peserta yang terdaftar di batch
materials           — materi belajar per batch
assignments         — tugas per batch
assignment_submissions — pengumpulan tugas peserta
tests               — ujian per batch
questions           — soal ujian (pilihan ganda)
question_options    — pilihan jawaban per soal
test_attempts       — hasil pengerjaan ujian peserta
test_answers        — jawaban per soal dari peserta
forms               — form/kuesioner
form_questions      — pertanyaan form
form_options        — pilihan jawaban form
form_responses      — respons peserta
form_answers        — jawaban per pertanyaan
meetings            — pertemuan/sesi tatap muka
attendances         — rekap kehadiran
certificates        — sertifikat kelulusan
participant_notes   — catatan per peserta
```

---

## Struktur Project

```
katekese_lms/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/              # Controller untuk role katekis
│   │   │   │   ├── AssignmentController.php
│   │   │   │   ├── BatchController.php
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── MaterialController.php
│   │   │   │   ├── ProgramController.php
│   │   │   │   ├── TestController.php
│   │   │   │   └── UserController.php
│   │   │   ├── Auth/               # Login & Register
│   │   │   │   ├── LoginController.php
│   │   │   │   └── RegisterController.php
│   │   │   ├── Katekis/            # (reserved)
│   │   │   │   └── DashboardController.php
│   │   │   └── Peserta/            # Controller untuk role peserta
│   │   │       ├── AssignmentController.php
│   │   │       ├── DashboardController.php
│   │   │       ├── EnrollmentController.php
│   │   │       ├── GradeController.php
│   │   │       ├── MaterialController.php
│   │   │       └── TestController.php
│   │   └── Middleware/
│   │       └── RoleMiddleware.php  # Guard berbasis role
│   ├── Models/                     # Eloquent models
│   │   ├── User.php
│   │   ├── Program.php
│   │   ├── Batch.php
│   │   ├── Material.php
│   │   ├── Module.php
│   │   ├── Meeting.php
│   │   ├── Attendance.php
│   │   ├── Assignment.php
│   │   ├── AssignmentSubmission.php
│   │   ├── Test.php
│   │   ├── Question.php
│   │   ├── QuestionOption.php
│   │   ├── TestAttempt.php
│   │   ├── TestAnswer.php
│   │   ├── Form.php
│   │   ├── FormQuestion.php
│   │   ├── FormOption.php
│   │   ├── FormResponse.php
│   │   ├── FormAnswer.php
│   │   ├── Certificate.php
│   │   └── ParticipantNote.php
│   └── Providers/
│       └── AppServiceProvider.php
│
├── database/
│   ├── migrations/                 # Skema tabel database
│   ├── seeders/                    # Data awal (admin, program, user)
│   │   ├── AdminSeeder.php
│   │   ├── ProgramSeeder.php
│   │   └── UserSeeder.php
│   └── database.sqlite             # File database SQLite (development)
│
├── resources/
│   ├── css/
│   │   └── app.css                 # Entry point Tailwind CSS
│   ├── js/
│   │   ├── app.js
│   │   └── bootstrap.js
│   └── views/
│       ├── admin/                  # Halaman panel katekis
│       │   ├── assignments/
│       │   ├── batches/
│       │   ├── materials/
│       │   ├── programs/
│       │   ├── tests/
│       │   ├── users/
│       │   └── dashboard.blade.php
│       ├── auth/                   # Halaman login & register
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── components/             # Komponen Blade reusable
│       │   └── nav-link.blade.php
│       ├── katekis/
│       │   └── dashboard.blade.php
│       ├── layouts/
│       │   └── app.blade.php       # Layout utama (sidebar + header)
│       ├── peserta/                # Halaman panel peserta
│       │   ├── kelas/
│       │   ├── materi/
│       │   ├── nilai/
│       │   ├── test/
│       │   ├── tugas/
│       │   └── dashboard.blade.php
│       └── welcome.blade.php
│
├── routes/
│   └── web.php                     # Semua definisi route
│
├── public/
│   ├── build/                      # Hasil build Vite (CSS + JS)
│   └── storage/                    # Symlink ke storage/app/public
│
├── storage/
│   ├── app/public/                 # Upload file (materi, dll)
│   ├── framework/                  # Cache view, session, dll
│   └── logs/                       # Log aplikasi
│
├── config/                         # Konfigurasi Laravel
├── tests/                          # Unit & Feature tests (PHPUnit)
├── .env.example                    # Template environment variable
├── composer.json                   # Dependency PHP
├── package.json                    # Dependency JS/Node
└── vite.config.js                  # Konfigurasi Vite
```

---

## Routing

Semua route didefinisikan di `routes/web.php` dengan struktur:

| Prefix | Middleware | Deskripsi |
|---|---|---|
| `/login`, `/register` | `guest` | Autentikasi |
| `/admin/*` | `auth`, `role:katekis` | Panel katekis |
| `/peserta/*` | `auth`, `role:peserta` | Panel peserta |

---

## Cara Menjalankan (Development)

```bash
# Install semua dependensi sekaligus
composer run setup

# Jalankan server development (Laravel + Vite + Queue + Pail)
composer run dev
```

Atau manual:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
npm install
npm run dev
php artisan serve
```

---

## Konfigurasi Environment Penting (`.env`)

```env
APP_NAME=Laravel
APP_ENV=local
DB_CONNECTION=sqlite          # Ganti ke mysql untuk production
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
FILESYSTEM_DISK=local
```
