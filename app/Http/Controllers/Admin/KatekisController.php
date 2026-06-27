<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KatekisController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['programs', 'profile'])->where('role', 'katekis');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('program_id')) {
            $query->whereHas('programs', function ($q) use ($request) {
                $q->where('programs.id', $request->program_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'aktif');
        }

        $katekisList = $query->orderBy('name')->paginate(15)->withQueryString();

        $programs = Program::orderBy('name')->get();

        return view('admin.katekis.index', compact('katekisList', 'programs'));
    }

    public function show(User $katekis)
    {
        abort_unless($katekis->role === 'katekis', 404);

        $katekis->load(['programs', 'profile', 'batchesAsKatekis.program']);

        return view('admin.katekis.show', compact('katekis'));
    }

    public function edit(User $katekis)
    {
        abort_unless($katekis->role === 'katekis', 404);

        $programs = Program::orderBy('name')->get();
        $selectedPrograms = $katekis->programs()->pluck('programs.id')->toArray();

        return view('admin.katekis.edit', compact('katekis', 'programs', 'selectedPrograms'));
    }

    public function update(Request $request, User $katekis)
    {
        abort_unless($katekis->role === 'katekis', 404);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $katekis->id,
            'phone'    => 'required|string|max:20',
            'bidang'   => 'nullable|array',
            'bidang.*' => 'exists:programs,id',
        ]);

        $katekis->update([
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        $katekis->programs()->sync($request->input('bidang', []));

        return redirect()->route('admin.katekis.show', $katekis)->with('success', 'Data katekis berhasil diperbarui.');
    }

    public function toggleActive(User $katekis)
    {
        abort_unless($katekis->role === 'katekis', 404);

        if ($katekis->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun sendiri.');
        }

        $katekis->update(['is_active' => ! $katekis->is_active]);

        $status = $katekis->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Katekis berhasil {$status}.");
    }

    public function resetPassword(User $katekis)
    {
        abort_unless($katekis->role === 'katekis', 404);

        $newPassword = 'katekis123';

        $katekis->update(['password' => Hash::make($newPassword)]);

        return back()->with('success', "Password direset ke: {$newPassword}");
    }
}
