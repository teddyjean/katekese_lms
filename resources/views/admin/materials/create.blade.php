@extends('layouts.app')
@section('title', 'Upload Materi')
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.materials.index') }}" class="text-sm text-gray-500 hover:text-blue-600">&larr; Kembali</a>
    <h1 class="text-2xl font-bold text-gray-800 mt-2">Upload Materi</h1>
</div>
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-xl">
    <form method="POST" action="{{ route('admin.materials.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
            <select name="batch_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('batch_id') border-red-400 @enderror">
                <option value="">-- Pilih Kelas --</option>
                @foreach($batches as $batch)
                    <option value="{{ $batch->id }}" @selected(old('batch_id') == $batch->id)>{{ $batch->name }} ({{ $batch->program->name }})</option>
                @endforeach
            </select>
            @error('batch_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Materi</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('title') border-red-400 @enderror">
            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-gray-400">(opsional)</span></label>
            <textarea name="description" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">{{ old('description') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">File</label>
            <input type="file" name="file" class="w-full text-sm text-gray-600 border border-gray-200 rounded-lg px-3 py-2 @error('file') border-red-400 @enderror">
            <p class="text-gray-400 text-xs mt-1">PDF, Word, PPT, gambar, atau MP4. Maks 20MB.</p>
            @error('file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="pt-2 flex gap-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">Upload</button>
            <a href="{{ route('admin.materials.index') }}" class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2">Batal</a>
        </div>
    </form>
</div>
@endsection
