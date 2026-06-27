@extends('layouts.app')

@section('title', 'Edit Angkatan')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.batches.show', $batch) }}" class="text-sm text-gray-500 hover:text-blue-600">&larr; Kembali</a>
    <h1 class="text-2xl font-bold text-gray-800 mt-2">Edit Angkatan</h1>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-xl">
    <form method="POST" action="{{ route('admin.batches.update', $batch) }}" class="space-y-4">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Program</label>
            <select name="program_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('program_id') border-red-400 @enderror">
                @foreach($programs as $program)
                    <option value="{{ $program->id }}" @selected(old('program_id', $batch->program_id) == $program->id)>{{ $program->name }}</option>
                @endforeach
            </select>
            @error('program_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Angkatan</label>
            <input type="text" name="name" value="{{ old('name', $batch->name) }}"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('name') border-red-400 @enderror">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ old('start_date', $batch->start_date?->format('Y-m-d')) }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ old('end_date', $batch->end_date?->format('Y-m-d')) }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                <option value="active"    @selected(old('status', $batch->status) === 'active')>Aktif</option>
                <option value="completed" @selected(old('status', $batch->status) === 'completed')>Selesai</option>
                <option value="archived"  @selected(old('status', $batch->status) === 'archived')>Arsip</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-gray-400">(opsional)</span></label>
            <textarea name="description" rows="3"
                      class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">{{ old('description', $batch->description) }}</textarea>
        </div>

        <hr class="border-gray-100">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Data Dokumen Resmi</p>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Romo <span class="text-gray-400">(opsional)</span></label>
            <input type="text" name="nama_romo" value="{{ old('nama_romo', $batch->nama_romo) }}"
                   placeholder="cth. Rm. Yohanes Budi, Pr"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Penerimaan Sakramen <span class="text-gray-400">(opsional)</span></label>
            <input type="date" name="tanggal_sakramen" value="{{ old('tanggal_sakramen', $batch->tanggal_sakramen?->format('Y-m-d')) }}"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
        </div>

        <div class="pt-2 flex gap-3">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.batches.show', $batch) }}"
               class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2">Batal</a>
        </div>
    </form>
</div>
@endsection
