<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::withCount('batches')->orderBy('order')->orderBy('name')->get();

        return view('admin.programs.index', compact('programs'));
    }

    public function create()
    {
        return view('admin.programs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:programs,name',
            'description' => 'nullable|string',
            'order'       => 'nullable|integer|min:1|max:255',
        ]);

        Program::create([
            'name'        => $request->name,
            'description' => $request->description,
            'order'       => $request->order,
            'status'      => 'active',
        ]);

        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil ditambahkan.');
    }

    public function edit(Program $program)
    {
        return view('admin.programs.edit', compact('program'));
    }

    public function update(Request $request, Program $program)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:programs,name,' . $program->id,
            'description' => 'nullable|string',
            'order'       => 'nullable|integer|min:1|max:255',
        ]);

        $program->update([
            'name'        => $request->name,
            'description' => $request->description,
            'order'       => $request->order,
        ]);

        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil diperbarui.');
    }

    public function toggleStatus(Program $program)
    {
        $newStatus = $program->status === 'active' ? 'archived' : 'active';
        $program->update(['status' => $newStatus]);

        $label = $newStatus === 'active' ? 'diaktifkan' : 'diarsipkan';

        return back()->with('success', "Program berhasil {$label}.");
    }
}
