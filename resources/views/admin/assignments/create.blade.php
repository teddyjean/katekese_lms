@extends('layouts.app')
@section('title', 'Buat Tugas')
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.assignments.index') }}" class="text-sm text-gray-500 hover:text-blue-600">&larr; Kembali</a>
    <h1 class="text-2xl font-bold text-gray-800 mt-2">Buat Tugas</h1>
</div>
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-xl">
    <form method="POST" action="{{ route('admin.assignments.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
            <select name="batch_id" id="batch_id" onchange="filterMaterials()" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('batch_id') border-red-400 @enderror">
                <option value="">-- Pilih Kelas --</option>
                @foreach($batches as $batch)
                    <option value="{{ $batch->id }}" @selected(old('batch_id') == $batch->id)>{{ $batch->name }} ({{ $batch->program->name }})</option>
                @endforeach
            </select>
            @error('batch_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Materi <span class="text-gray-400">(opsional)</span></label>
            <select name="material_id" id="material_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('material_id') border-red-400 @enderror">
                <option value="">-- Tanpa Materi (Umum) --</option>
            </select>
            @error('material_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Tugas</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('title') border-red-400 @enderror">
            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi / Instruksi</label>
            <textarea name="description" rows="5" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">{{ old('description') }}</textarea>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deadline <span class="text-gray-400">(opsional)</span></label>
                <input type="datetime-local" name="deadline" value="{{ old('deadline') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nilai Maksimum</label>
                <input type="number" name="max_score" value="{{ old('max_score', 100) }}" min="1" max="1000" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('max_score') border-red-400 @enderror">
                @error('max_score') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        <div class="pt-2 flex gap-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">Simpan</button>
            <a href="{{ route('admin.assignments.index') }}" class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2">Batal</a>
        </div>
    </form>
</div>

<script>
const materialsData = @json($materialsByBatch);
const savedMaterial = "{{ old('material_id') }}";

function filterMaterials() {
    const batchId = document.getElementById('batch_id').value;
    const sel = document.getElementById('material_id');
    sel.innerHTML = '<option value="">-- Tanpa Materi (Umum) --</option>';

    if (batchId && materialsData[batchId]) {
        materialsData[batchId].forEach(function(m) {
            const opt = document.createElement('option');
            opt.value = m.id;
            opt.textContent = m.title;
            if (String(m.id) === String(savedMaterial)) opt.selected = true;
            sel.appendChild(opt);
        });
    }
}

document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('batch_id').value) {
        filterMaterials();
    }
});
</script>
@endsection
