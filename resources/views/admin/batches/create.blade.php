@extends('layouts.app')

@section('title', 'Buat Kelas Baru')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.batches.index') }}" class="text-sm text-gray-500 hover:text-blue-600">&larr; Kembali ke Kelas</a>
    <h1 class="text-2xl font-bold text-gray-800 mt-2">Buat Kelas Baru</h1>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-xl">
    <form method="POST" action="{{ route('admin.batches.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Katekese</label>
            <select name="program_id" id="program_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('program_id') border-red-400 @enderror">
                <option value="">-- Pilih Jenis (Baptis / Komuni / Krisma) --</option>
                @foreach($programs as $program)
                    <option value="{{ $program->id }}" @selected(old('program_id') == $program->id)>{{ $program->name }}</option>
                @endforeach
            </select>
            @error('program_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div id="katekis-section" class="hidden">
            <label class="block text-sm font-medium text-gray-700 mb-1">Katekis Pengajar</label>
            <div id="katekis-list" class="border border-gray-200 rounded-lg p-3 space-y-2 max-h-48 overflow-y-auto"></div>
            <p id="katekis-empty" class="hidden text-sm text-amber-600 mt-1">
                Belum ada katekis yang terdaftar untuk program ini.
            </p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kelas / Angkatan</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   placeholder="Contoh: Krisma 2026, Komuni Pertama Angkatan I 2026"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('name') border-red-400 @enderror">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ old('start_date') }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('start_date') border-red-400 @enderror">
                @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ old('end_date') }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('end_date') border-red-400 @enderror">
                @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-gray-400">(opsional)</span></label>
            <textarea name="description" rows="3"
                      class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">{{ old('description') }}</textarea>
        </div>

        <div class="pt-2 flex gap-3">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                Buat Kelas
            </button>
            <a href="{{ route('admin.batches.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2">Batal</a>
        </div>
    </form>
</div>
@push('scripts')
<script>
    const katekisByProgram = @json($katekisByProgram);
    const oldKatekis = @json(old('katekis_ids', []));

    const programSelect  = document.getElementById('program_id');
    const katekisSection = document.getElementById('katekis-section');
    const katekisList    = document.getElementById('katekis-list');
    const katekisEmpty   = document.getElementById('katekis-empty');

    function renderKatekis(programId) {
        katekisList.innerHTML = '';
        const list = katekisByProgram[programId] ?? [];

        if (!programId) {
            katekisSection.classList.add('hidden');
            return;
        }

        katekisSection.classList.remove('hidden');

        if (list.length === 0) {
            katekisEmpty.classList.remove('hidden');
            katekisList.classList.add('hidden');
            return;
        }

        katekisEmpty.classList.add('hidden');
        katekisList.classList.remove('hidden');

        list.forEach(k => {
            const checked = oldKatekis.includes(String(k.id)) ? 'checked' : '';
            katekisList.insertAdjacentHTML('beforeend', `
                <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                    <input type="checkbox" name="katekis_ids[]" value="${k.id}" ${checked}
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-300">
                    ${k.name}
                </label>
            `);
        });
    }

    programSelect.addEventListener('change', e => renderKatekis(e.target.value));
    renderKatekis(programSelect.value);
</script>
@endpush
@endsection
