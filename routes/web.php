<?php

use App\Http\Controllers\Admin\AssignmentController;
use App\Http\Controllers\Admin\BatchController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\KatekisController;
use App\Http\Controllers\Admin\MaterialAssessmentController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\MeetingController;
use App\Http\Controllers\Admin\ProfileController as KatekisProfileController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TestController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Peserta\AssignmentController as PesertaAssignmentController;
use App\Http\Controllers\Peserta\AttendanceController as PesertaAttendanceController;
use App\Http\Controllers\Peserta\DashboardController as PesertaDashboard;
use App\Http\Controllers\Peserta\EnrollmentController;
use App\Http\Controllers\Peserta\GradeController as PesertaGradeController;
use App\Http\Controllers\Peserta\MaterialController as PesertaMaterialController;
use App\Http\Controllers\Peserta\ProfileController as PesertaProfileController;
use App\Http\Controllers\Peserta\TestController as PesertaTestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        return redirect()->route($role === 'katekis' ? 'admin.dashboard' : 'peserta.dashboard');
    }
    return redirect()->route('login');
});

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Admin (dikelola oleh katekis)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:katekis'])->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Profil Saya (katekis)
    Route::get('/profile', [KatekisProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [KatekisProfileController::class, 'update'])->name('profile.update');

    // Siswa
    Route::get('siswa', [StudentController::class, 'index'])->name('students.index');
    Route::get('siswa/{student}', [StudentController::class, 'show'])->name('students.show');
    Route::get('siswa/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('siswa/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::patch('siswa/{student}/toggle-active', [StudentController::class, 'toggleActive'])->name('students.toggle-active');
    Route::patch('siswa/{student}/reset-password', [StudentController::class, 'resetPassword'])->name('students.reset-password');

    // Katekis
    Route::get('katekis', [KatekisController::class, 'index'])->name('katekis.index');
    Route::get('katekis/{katekis}', [KatekisController::class, 'show'])->name('katekis.show');
    Route::get('katekis/{katekis}/edit', [KatekisController::class, 'edit'])->name('katekis.edit');
    Route::put('katekis/{katekis}', [KatekisController::class, 'update'])->name('katekis.update');
    Route::patch('katekis/{katekis}/toggle-active', [KatekisController::class, 'toggleActive'])->name('katekis.toggle-active');
    Route::patch('katekis/{katekis}/reset-password', [KatekisController::class, 'resetPassword'])->name('katekis.reset-password');

    // Program Management
    Route::resource('programs', ProgramController::class)->only(['index', 'create', 'store', 'edit', 'update']);
    Route::patch('programs/{program}/toggle-status', [ProgramController::class, 'toggleStatus'])->name('programs.toggle-status');

    // Batch / Angkatan Management
    Route::resource('batches', BatchController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update']);
    Route::post('batches/{batch}/katekis', [BatchController::class, 'assignKatekis'])->name('batches.katekis.assign');
    Route::delete('batches/{batch}/katekis/{user}', [BatchController::class, 'removeKatekis'])->name('batches.katekis.remove');
    Route::post('batches/{batch}/peserta', [BatchController::class, 'assignPeserta'])->name('batches.peserta.assign');
    Route::delete('batches/{batch}/peserta/{user}', [BatchController::class, 'removePeserta'])->name('batches.peserta.remove');
    Route::post('batches/{batch}/peserta/{user}/approve', [BatchController::class, 'approvePeserta'])->name('batches.peserta.approve');
    Route::post('batches/{batch}/peserta/{user}/reject', [BatchController::class, 'rejectPeserta'])->name('batches.peserta.reject');
    Route::post('batches/{batch}/peserta/{user}/transfer', [BatchController::class, 'transferPeserta'])->name('batches.peserta.transfer');
    Route::patch('batches/{batch}/peserta/{user}/kelulusan', [BatchController::class, 'updateKelulusan'])->name('batches.peserta.kelulusan');
    Route::patch('batches/{batch}/dokumen', [BatchController::class, 'updateDocumentFields'])->name('batches.update-document');

    // Presensi / Pertemuan
    Route::post('batches/{batch}/meetings', [MeetingController::class, 'store'])->name('meetings.store');
    Route::delete('meetings/{meeting}', [MeetingController::class, 'destroy'])->name('meetings.destroy');
    Route::get('meetings/{meeting}/presensi', [MeetingController::class, 'editAttendance'])->name('meetings.attendance.edit');
    Route::put('meetings/{meeting}/presensi', [MeetingController::class, 'updateAttendance'])->name('meetings.attendance.update');

    // Materi
    Route::resource('materials', MaterialController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::get('materials/{material}/penilaian', [MaterialAssessmentController::class, 'edit'])->name('materials.assessments.edit');
    Route::put('materials/{material}/penilaian', [MaterialAssessmentController::class, 'update'])->name('materials.assessments.update');

    // Tugas
    Route::resource('assignments', AssignmentController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::patch('submissions/{submission}/grade', [AssignmentController::class, 'grade'])->name('submissions.grade');

    // Test
    Route::resource('tests', TestController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::patch('tests/{test}/toggle-active', [TestController::class, 'toggleActive'])->name('tests.toggle-active');
    Route::post('tests/{test}/questions', [TestController::class, 'storeQuestion'])->name('tests.questions.store');
    Route::delete('tests/{test}/questions/{question}', [TestController::class, 'destroyQuestion'])->name('tests.questions.destroy');
});

// Peserta
Route::prefix('peserta')->name('peserta.')->middleware(['auth', 'role:peserta'])->group(function () {
    Route::get('/dashboard', [PesertaDashboard::class, 'index'])->name('dashboard');

    // Profil
    Route::get('/profile', [PesertaProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [PesertaProfileController::class, 'update'])->name('profile.update');

    // Kelas / Enrollment
    Route::get('/kelas', [EnrollmentController::class, 'index'])->name('kelas.index');
    Route::post('/kelas/daftar', [EnrollmentController::class, 'store'])->name('kelas.daftar');

    // Materi
    Route::get('/materi', [PesertaMaterialController::class, 'index'])->name('materi.index');
    Route::get('/materi/{material}', [PesertaMaterialController::class, 'show'])->name('materi.show');

    // Tugas
    Route::get('/tugas', [PesertaAssignmentController::class, 'index'])->name('tugas.index');
    Route::get('/tugas/{assignment}', [PesertaAssignmentController::class, 'show'])->name('tugas.show');
    Route::post('/tugas/{assignment}/submit', [PesertaAssignmentController::class, 'submit'])->name('tugas.submit');

    // Test
    Route::get('/test', [PesertaTestController::class, 'index'])->name('test.index');
    Route::get('/test/{test}', [PesertaTestController::class, 'show'])->name('test.show');
    Route::post('/test/{test}/submit', [PesertaTestController::class, 'submit'])->name('test.submit');
    Route::get('/test/{test}/result', [PesertaTestController::class, 'result'])->name('test.result');

    // Nilai
    Route::get('/nilai', [PesertaGradeController::class, 'index'])->name('nilai.index');

    // Presensi
    Route::get('/presensi', [PesertaAttendanceController::class, 'index'])->name('presensi.index');
    Route::post('/presensi/{meeting}/hadir', [PesertaAttendanceController::class, 'checkin'])->name('presensi.checkin');
});
