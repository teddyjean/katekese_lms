@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.students.show', $student) }}" class="text-sm text-gray-500 hover:text-blue-600">&larr; Kembali</a>
    <h1 class="text-2xl font-bold text-gray-800 mt-2">Edit Siswa</h1>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl">
    <form method="POST" action="{{ route('admin.students.update', $student) }}" class="space-y-7">
        @csrf @method('PUT')

        {{-- Data Akun --}}
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">Data Akun</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $student->name) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('name') border-red-400 @enderror">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $student->email) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('email') border-red-400 @enderror">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No HP</label>
                    <input type="text" name="phone" value="{{ old('phone', $student->phone) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('phone') border-red-400 @enderror">
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Data Pribadi --}}
        <div class="border-t border-gray-100 pt-6">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">Data Pribadi</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir"
                           value="{{ old('tanggal_lahir', $student->profile?->tanggal_lahir?->format('Y-m-d')) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('tanggal_lahir') border-red-400 @enderror">
                    @error('tanggal_lahir') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sekolah</label>
                    <input type="text" name="sekolah" value="{{ old('sekolah', $student->profile?->sekolah) }}"
                           placeholder="Nama sekolah"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('sekolah') border-red-400 @enderror">
                    @error('sekolah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kelas / Tingkat</label>
                    <input type="text" name="kelas" value="{{ old('kelas', $student->profile?->kelas) }}"
                           placeholder="Contoh: 7A, XI IPA 2"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('kelas') border-red-400 @enderror">
                    @error('kelas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ayah</label>
                    <input type="text" name="nama_ayah" value="{{ old('nama_ayah', $student->profile?->nama_ayah) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('nama_ayah') border-red-400 @enderror">
                    @error('nama_ayah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ibu</label>
                    <input type="text" name="nama_ibu" value="{{ old('nama_ibu', $student->profile?->nama_ibu) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('nama_ibu') border-red-400 @enderror">
                    @error('nama_ibu') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Lokasi --}}
        <div class="border-t border-gray-100 pt-6">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">Lokasi</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Wilayah</label>
                    <select name="wilayah" id="wilayah"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('wilayah') border-red-400 @enderror"
                            onchange="filterLingkungan()">
                        <option value="">-- Pilih Wilayah --</option>
                        @foreach(array_keys($wilayahLingkungan) as $wil)
                            <option value="{{ $wil }}" {{ old('wilayah', $student->profile?->wilayah) === $wil ? 'selected' : '' }}>
                                {{ $wil }}
                            </option>
                        @endforeach
                    </select>
                    @error('wilayah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lingkungan</label>
                    <select name="lingkungan" id="lingkungan"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('lingkungan') border-red-400 @enderror">
                        <option value="">-- Pilih Lingkungan --</option>
                    </select>
                    @error('lingkungan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="alamat" rows="2"
                              placeholder="Alamat lengkap"
                              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('alamat') border-red-400 @enderror">{{ old('alamat', $student->profile?->alamat) }}</textarea>
                    @error('alamat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Data Sakramen --}}
        <div class="border-t border-gray-100 pt-6">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Data Sakramen</p>
            <p class="text-xs text-gray-400 mb-4">Menentukan program katekese yang dapat diikuti siswa.</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Baptis</label>
                    <input type="text" name="nama_baptis" value="{{ old('nama_baptis', $student->profile?->nama_baptis) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('nama_baptis') border-red-400 @enderror">
                    @error('nama_baptis') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gereja Tempat Dibaptis</label>
                    <input type="text" name="gereja_baptis" value="{{ old('gereja_baptis', $student->profile?->gereja_baptis) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('gereja_baptis') border-red-400 @enderror">
                    @error('gereja_baptis') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Surat Baptis</label>
                    <input type="text" name="nomor_buku_baptis" value="{{ old('nomor_buku_baptis', $student->profile?->nomor_buku_baptis) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('nomor_buku_baptis') border-red-400 @enderror">
                    @error('nomor_buku_baptis') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gereja Tempat Komuni Pertama</label>
                    <input type="text" name="gereja_komuni_pertama" value="{{ old('gereja_komuni_pertama', $student->profile?->gereja_komuni_pertama) }}"
                           placeholder="Kosongkan jika belum Komuni Pertama"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('gereja_komuni_pertama') border-red-400 @enderror">
                    @error('gereja_komuni_pertama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="pt-2 border-t border-gray-100 flex gap-3">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.students.show', $student) }}"
               class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2">Batal</a>
        </div>
    </form>
</div>

<script>
const wilayahData = @json($wilayahLingkungan);
const savedLingkungan = "{{ old('lingkungan', $student->profile?->lingkungan) }}";

function filterLingkungan() {
    const wil = document.getElementById('wilayah').value;
    const sel = document.getElementById('lingkungan');
    sel.innerHTML = '<option value="">-- Pilih Lingkungan --</option>';

    if (wil && wilayahData[wil]) {
        wilayahData[wil].forEach(function(ling) {
            const opt = document.createElement('option');
            opt.value = ling;
            opt.textContent = ling;
            if (ling === savedLingkungan) opt.selected = true;
            sel.appendChild(opt);
        });
    }
}

document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('wilayah').value) {
        filterLingkungan();
    }
});
</script>

@endsection
