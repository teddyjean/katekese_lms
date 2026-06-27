<?php

namespace App\Http\Controllers\Peserta;

use App\Data\WilayahLingkungan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $profile = auth()->user()->profile;
        $wilayahLingkungan = WilayahLingkungan::all();
        return view('peserta.profile.edit', compact('profile', 'wilayahLingkungan'));
    }

    public function update(Request $request)
    {
        $data = WilayahLingkungan::all();
        $validWilayah    = array_keys($data);
        $validLingkungan = $request->filled('wilayah') ? ($data[$request->wilayah] ?? []) : [];

        $request->validate([
            'nama_baptis'   => 'nullable|string|max:255',
            'sekolah'       => 'required|string|max:255',
            'kelas'         => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'wilayah'       => ['required', 'string', 'in:' . implode(',', $validWilayah)],
            'lingkungan'    => ['required', 'string', 'in:' . implode(',', $validLingkungan)],
        ]);

        auth()->user()->profile()->updateOrCreate(
            ['user_id' => auth()->id()],
            $request->only('nama_baptis', 'sekolah', 'kelas', 'tanggal_lahir', 'wilayah', 'lingkungan')
        );

        return back()->with('success', 'Profil berhasil disimpan.');
    }
}
