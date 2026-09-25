<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('profile')->where('role', 'peserta');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $students = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.students.index', compact('students'));
    }

    public function show(User $student)
    {
        abort_unless($student->role === 'peserta', 404);

        $student->load('profile');

        $enrollments = $student->batchesAsPeserta()
            ->with('program')
            ->orderByDesc('batch_participants.created_at')
            ->get();

        return view('admin.students.show', compact('student', 'enrollments'));
    }

    public function toggleActive(User $student)
    {
        abort_unless($student->role === 'peserta', 404);
        Gate::authorize('manageStudent', $student);

        $student->update(['is_active' => ! $student->is_active]);

        $status = $student->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Siswa berhasil {$status}.");
    }

    public function resetPassword(User $student)
    {
        abort_unless($student->role === 'peserta', 404);
        Gate::authorize('manageStudent', $student);

        $newPassword = Str::random(10);

        $student->update(['password' => Hash::make($newPassword)]);

        return back()->with('success', "Password direset ke: {$newPassword}");
    }
}
