<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['profile', 'approvedBatchesAsPeserta.program'])->where('role', 'peserta');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('program_id')) {
            $query->whereHas('batchesAsPeserta', function ($q) use ($request) {
                $q->where('batch_participants.status', 'approved')->where('batches.program_id', $request->program_id);
            });
        }

        if ($request->filled('batch_id')) {
            $query->whereHas('batchesAsPeserta', function ($q) use ($request) {
                $q->where('batch_participants.status', 'approved')->where('batches.id', $request->batch_id);
            });
        }

        if ($request->filled('status')) {
            match ($request->status) {
                'aktif' => $query->whereHas('batchesAsPeserta', function ($q) {
                    $q->where('batch_participants.status', 'approved')->where('batches.status', 'active');
                }),
                'lulus' => $query->whereHas('batchesAsPeserta', function ($q) {
                    $q->where('batch_participants.status', 'approved')->where('batches.status', 'completed');
                }),
                'belum' => $query->whereDoesntHave('batchesAsPeserta', function ($q) {
                    $q->where('batch_participants.status', 'approved');
                }),
                default => null,
            };
        }

        $students = $query->orderBy('name')->paginate(15)->withQueryString();

        $programs = Program::orderBy('name')->get();
        $batches = Batch::orderBy('name')->get();

        return view('admin.students.index', compact('students', 'programs', 'batches'));
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

    public function edit(User $student)
    {
        abort_unless($student->role === 'peserta', 404);

        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, User $student)
    {
        abort_unless($student->role === 'peserta', 404);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->id,
            'phone' => 'required|string|max:20',
        ]);

        $student->update([
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.students.show', $student)->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function toggleActive(User $student)
    {
        abort_unless($student->role === 'peserta', 404);

        $student->update(['is_active' => ! $student->is_active]);

        $status = $student->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Siswa berhasil {$status}.");
    }

    public function resetPassword(User $student)
    {
        abort_unless($student->role === 'peserta', 404);

        $newPassword = 'katekis123';

        $student->update(['password' => Hash::make($newPassword)]);

        return back()->with('success', "Password direset ke: {$newPassword}");
    }
}
