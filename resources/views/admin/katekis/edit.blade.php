@extends('layouts.app')

@section('title', 'Edit Katekis')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.katekis.show', $katekis) }}" class="text-sm text-gray-500 hover:text-blue-600">&larr; Kembali</a>
    <h1 class="text-2xl font-bold text-gray-800 mt-2">Edit Katekis</h1>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-xl">
    <form method="POST" action="{{ route('admin.katekis.update', $katekis) }}" class="space-y-4">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $katekis->name) }}"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('name') border-red-400 @enderror">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $katekis->email) }}"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('email') border-red-400 @enderror">
            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">No HP</label>
            <input type="text" name="phone" value="{{ old('phone', $katekis->phone) }}" required
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('phone') border-red-400 @enderror">
            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Bidang <span class="text-gray-400">(program yang bisa diajar)</span></label>
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
            @error('bidang') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="pt-2 flex gap-3">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.katekis.show', $katekis) }}"
               class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2">Batal</a>
        </div>
    </form>
</div>
@endsection
