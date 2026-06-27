<?php

namespace App\Http\Controllers\Admin;

use App\Data\WilayahLingkungan;
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

        $student->load('profile');
        $wilayahLingkungan = WilayahLingkungan::all();

        return view('admin.students.edit', compact('student', 'wilayahLingkungan'));
    }

    public function update(Request $request, User $student)
    {
        abort_unless($student->role === 'peserta', 404);

        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email,' . $student->id,
            'phone'                 => 'required|string|max:20',
            'nama_baptis'           => 'nullable|string|max:255',
            'nama_ayah'             => 'nullable|string|max:255',
            'nama_ibu'              => 'nullable|string|max:255',
            'gereja_baptis'         => 'nullable|string|max:255',
            'nomor_buku_baptis'     => 'nullable|string|max:100',
            'gereja_komuni_pertama' => 'nullable|string|max:255',
            'alamat'                => 'nullable|string|max:1000',
            'sekolah'               => 'nullable|string|max:255',
            'kelas'                 => 'nullable|string|max:100',
            'tanggal_lahir'         => 'nullable|date|before:today',
            'wilayah'               => 'nullable|string',
            'lingkungan'            => 'nullable|string',
        ]);

        $student->update([
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        $profileData = array_filter(
            $request->only(
                'nama_baptis', 'nama_ayah', 'nama_ibu',
                'gereja_baptis', 'nomor_buku_baptis', 'gereja_komuni_pertama',
                'alamat', 'sekolah', 'kelas', 'tanggal_lahir', 'wilayah', 'lingkungan'
            ),
            fn ($v) => $v !== null && $v !== ''
        );

        if (! empty($profileData)) {
            $student->profile()->updateOrCreate(
                ['user_id' => $student->id],
                $profileData
            );
        }

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
