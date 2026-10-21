@extends('layouts.app')
@section('title', 'Profil Saya')
@section('content')

<div class="max-w-2xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Profil Saya</h1>
        <p class="text-gray-500 text-sm mt-1">Data ini digunakan katekis untuk memverifikasi pendaftaran kelas Anda.</p>
    </div>

    @if(!$profile)
    <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 text-amber-800 rounded-2xl px-5 py-4 mb-6 text-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0 text-amber-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
        </svg>
        <div>
            <p class="font-semibold">Profil belum dilengkapi</p>
            <p class="mt-0.5 text-amber-700">Anda perlu mengisi profil sebelum dapat mendaftar ke kelas manapun.</p>
        </div>
    </div>
    @endif

    @if(session('success'))
    <div class="flex items-start gap-3 bg-green-50 border border-green-200 text-green-800 rounded-2xl px-5 py-4 mb-6 text-sm">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('peserta.profile.update') }}" class="space-y-7">
            @csrf @method('PUT')

            {{-- Data Akun --}}
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">Data Akun</p>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Nama Lengkap <span class="text-gray-400 font-normal">(dari akun)</span>
                    </label>
                    <input type="text" value="{{ auth()->user()->name }}" disabled
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-500 cursor-not-allowed">
                </div>
            </div>

            {{-- Data Pribadi --}}
            <div class="border-t border-gray-100 pt-6">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">Data Pribadi</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lahir"
                               value="{{ old('tanggal_lahir', $profile?->tanggal_lahir?->format('Y-m-d')) }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300
                                      @error('tanggal_lahir') border-red-400 @enderror">
                        @error('tanggal_lahir') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Sekolah <span class="text-red-500">*</span></label>
                        <input type="text" name="sekolah"
                               value="{{ old('sekolah', $profile?->sekolah) }}"
                               placeholder="Nama sekolah Anda"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300
                                      @error('sekolah') border-red-400 @enderror">
                        @error('sekolah') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kelas / Tingkat <span class="text-red-500">*</span></label>
                        <input type="text" name="kelas"
                               value="{{ old('kelas', $profile?->kelas) }}"
                               placeholder="Contoh: 7A, XI IPA 2, dst."
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300
                                      @error('kelas') border-red-400 @enderror">
                        @error('kelas') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Ayah</label>
                        <input type="text" name="nama_ayah"
                               value="{{ old('nama_ayah', $profile?->nama_ayah) }}"
                               placeholder="Nama lengkap ayah"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300
                                      @error('nama_ayah') border-red-400 @enderror">
                        @error('nama_ayah') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Ibu</label>
                        <input type="text" name="nama_ibu"
                               value="{{ old('nama_ibu', $profile?->nama_ibu) }}"
                               placeholder="Nama lengkap ibu"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300
                                      @error('nama_ibu') border-red-400 @enderror">
                        @error('nama_ibu') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                </div>
            </div>

            {{-- Lokasi --}}
            <div class="border-t border-gray-100 pt-6">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">Lokasi</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Wilayah <span class="text-red-500">*</span></label>
                        <select name="wilayah" id="wilayah"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300
                                       @error('wilayah') border-red-400 @enderror"
                                onchange="filterLingkungan()">
                            <option value="">-- Pilih Wilayah --</option>
                            @foreach(array_keys($wilayahLingkungan) as $wil)
                                <option value="{{ $wil }}" {{ old('wilayah', $profile?->wilayah) === $wil ? 'selected' : '' }}>
                                    {{ $wil }}
                                </option>
                            @endforeach
                        </select>
                        @error('wilayah') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Lingkungan <span class="text-red-500">*</span></label>
                        <select name="lingkungan" id="lingkungan"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300
                                       @error('lingkungan') border-red-400 @enderror">
                            <option value="">-- Pilih Lingkungan --</option>
                        </select>
                        @error('lingkungan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat</label>
                        <textarea name="alamat" rows="2"
                                  placeholder="Alamat lengkap"
                                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300
                                         @error('alamat') border-red-400 @enderror">{{ old('alamat', $profile?->alamat) }}</textarea>
                        @error('alamat') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                </div>
            </div>

            {{-- Data Sakramen --}}
            <div class="border-t border-gray-100 pt-6">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Data Sakramen</p>
                <p class="text-xs text-gray-400 mb-4">Isi sesuai sakramen yang sudah Anda terima. Data ini menentukan program katekese yang dapat Anda ikuti.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Baptis</label>
                        <input type="text" name="nama_baptis"
                               value="{{ old('nama_baptis', $profile?->nama_baptis) }}"
                               placeholder="Contoh: Maria, Yohanes, dll."
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300
                                      @error('nama_baptis') border-red-400 @enderror">
                        @error('nama_baptis') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Gereja Tempat Dibaptis</label>
                        <input type="text" name="gereja_baptis"
                               value="{{ old('gereja_baptis', $profile?->gereja_baptis) }}"
                               placeholder="Contoh: Paroki St. Maria"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300
                                      @error('gereja_baptis') border-red-400 @enderror">
                        @error('gereja_baptis') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Surat Baptis</label>
                        <input type="text" name="nomor_buku_baptis"
                               value="{{ old('nomor_buku_baptis', $profile?->nomor_buku_baptis) }}"
                               placeholder="Nomor buku baptis"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300
                                      @error('nomor_buku_baptis') border-red-400 @enderror">
                        @error('nomor_buku_baptis') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Gereja Tempat Komuni Pertama</label>
                        <input type="text" name="gereja_komuni_pertama"
                               value="{{ old('gereja_komuni_pertama', $profile?->gereja_komuni_pertama) }}"
                               placeholder="Kosongkan jika belum Komuni Pertama"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300
                                      @error('gereja_komuni_pertama') border-red-400 @enderror">
                        @error('gereja_komuni_pertama') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                </div>
            </div>

            <div class="pt-2 border-t border-gray-100">
                <button type="submit"
                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-xl text-sm transition-colors shadow-sm">
                    Simpan Profil
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const wilayahData = @json($wilayahLingkungan);
const savedLingkungan = "{{ old('lingkungan', $profile?->lingkungan) }}";

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
