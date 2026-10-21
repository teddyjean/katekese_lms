<?php

namespace App\Http\Controllers\Admin;

use App\Data\WilayahLingkungan;
use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $profile = $user->profile;
        $programs = Program::orderBy('name')->get();
        $selectedPrograms = $user->programs()->pluck('programs.id')->toArray();
        $wilayahLingkungan = WilayahLingkungan::all();

        return view('admin.profile.edit', compact('profile', 'programs', 'selectedPrograms', 'wilayahLingkungan'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $data = WilayahLingkungan::all();
        $validWilayah    = array_keys($data);
        $validLingkungan = $request->filled('wilayah') ? ($data[$request->wilayah] ?? []) : [];

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'phone'       => 'required|string|max:20',
            'bidang'      => 'nullable|array',
            'bidang.*'    => 'exists:programs,id',
            'alamat'      => 'required|string',
            'wilayah'     => ['required', 'string', 'in:' . implode(',', $validWilayah)],
            'lingkungan'  => ['required', 'string', 'in:' . implode(',', $validLingkungan)],
        ]);

        $user->update($request->only('name', 'email', 'phone'));

        $user->programs()->sync($request->input('bidang', []));

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $request->only('alamat', 'wilayah', 'lingkungan')
        );

        return back()->with('success', 'Profil berhasil disimpan.');
    }
}
