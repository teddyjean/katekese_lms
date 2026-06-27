@extends('layouts.app')
@section('title', 'Profil Saya')
@section('content')

<div class="max-w-lg">
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

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('peserta.profile.update') }}" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Nama Lengkap
                        <span class="text-gray-400 font-normal">(dari akun)</span>
                    </label>
                    <input type="text" value="{{ auth()->user()->name }}" disabled
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-500 cursor-not-allowed">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Nama Baptis
                        <span class="text-gray-400 font-normal">(kosongkan jika belum dibaptis)</span>
                    </label>
                    <input type="text" name="nama_baptis"
                           value="{{ old('nama_baptis', $profile?->nama_baptis) }}"
                           placeholder="Contoh: Maria, Yohanes, dll."
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300
                                  @error('nama_baptis') border-red-400 @enderror">
                    @error('nama_baptis') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
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
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Lahir <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_lahir"
                           value="{{ old('tanggal_lahir', $profile?->tanggal_lahir?->format('Y-m-d')) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300
                                  @error('tanggal_lahir') border-red-400 @enderror">
                    @error('tanggal_lahir') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Wilayah --}}
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

                {{-- Lingkungan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Lingkungan <span class="text-red-500">*</span></label>
                    <select name="lingkungan" id="lingkungan"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300
                                   @error('lingkungan') border-red-400 @enderror">
                        <option value="">-- Pilih Lingkungan --</option>
                    </select>
                    @error('lingkungan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

            </div>

            <div class="pt-2">
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

// On page load, restore lingkungan if wilayah already selected
document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('wilayah').value) {
        filterLingkungan();
    }
});
</script>

@endsection
