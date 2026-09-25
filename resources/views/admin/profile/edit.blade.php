@extends('layouts.app')
@section('title', 'Profil Saya')
@section('content')

<div class="max-w-lg">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Profil Saya</h1>
        <p class="text-gray-500 text-sm mt-1">Lengkapi data diri Anda sebagai katekis.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300
                                  @error('name') border-red-400 @enderror">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300
                                  @error('email') border-red-400 @enderror">
                    @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">No HP</label>
                    <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300
                                  @error('phone') border-red-400 @enderror">
                    @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Katekese (Bidang) <span class="text-gray-400 font-normal">(program yang bisa diajar)</span></label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @foreach($programs as $program)
                            <label class="flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-2 text-sm cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="bidang[]" value="{{ $program->id }}"
                                       @checked(in_array($program->id, old('bidang', $selectedPrograms)))
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-300">
                                {{ $program->name }}
                            </label>
                        @endforeach
                    </div>
                    @error('bidang') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat</label>
                    <textarea name="alamat" rows="2"
                              class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300
                                     @error('alamat') border-red-400 @enderror">{{ old('alamat', $profile?->alamat) }}</textarea>
                    @error('alamat') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Wilayah --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Wilayah</label>
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
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Lingkungan</label>
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

document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('wilayah').value) {
        filterLingkungan();
    }
});
</script>

@endsection
