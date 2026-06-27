@extends('layouts.app')
@section('title', 'Tugas')
@section('content')
<div class="mb-6 flex items-start justify-between flex-wrap gap-y-3">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Tugas</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola tugas per kelas</p>
    </div>
    <a href="{{ route('admin.assignments.create') }}"
       class="shrink-0 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors shadow-sm">
        + Buat Tugas
    </a>
</div>

<form method="GET" class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6 flex gap-3 flex-wrap">
    <select name="batch_id" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
        <option value="">Semua Kelas</option>
        @foreach($batches as $batch)
            <option value="{{ $batch->id }}" @selected(request('batch_id') == $batch->id)>{{ $batch->name }}</option>
        @endforeach
    </select>
    <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-4 py-2 rounded-xl transition-colors">Filter</button>
    @if(request('batch_id'))
        <a href="{{ route('admin.assignments.index') }}" class="text-sm text-gray-400 hover:text-red-500 px-3 py-2 transition-colors">Reset</a>
    @endif
</form>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full min-w-[580px] text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wide">Judul</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wide">Kelas</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wide">Deadline</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wide">Nilai Maks</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wide">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($assignments as $assignment)
            <tr class="hover:bg-gray-50/80 transition-colors">
                <td class="px-5 py-3.5 font-medium text-gray-800">{{ $assignment->title }}</td>
                <td class="px-5 py-3.5 text-gray-500">{{ $assignment->batch->name }}</td>
                <td class="px-5 py-3.5 text-gray-500">
                    {{ $assignment->deadline ? $assignment->deadline->format('d M Y H:i') : '-' }}
                </td>
                <td class="px-5 py-3.5 text-gray-500">{{ $assignment->max_score }}</td>
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('admin.assignments.show', $assignment) }}"
                           class="text-xs font-medium bg-violet-50 text-violet-700 hover:bg-violet-100 px-2.5 py-1 rounded-lg transition-colors">
                            Submission
                        </a>
                        <a href="{{ route('admin.assignments.edit', $assignment) }}"
                           class="text-xs font-medium bg-blue-50 text-blue-700 hover:bg-blue-100 px-2.5 py-1 rounded-lg transition-colors">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.assignments.destroy', $assignment) }}"
                              onsubmit="return confirm('Hapus tugas ini?')">
                            @csrf @method('DELETE')
                            <button class="text-xs font-medium bg-red-50 text-red-600 hover:bg-red-100 px-2.5 py-1 rounded-lg transition-colors">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-12 text-center text-gray-400">Belum ada tugas.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if($assignments->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">{{ $assignments->links() }}</div>
    @endif
</div>
@endsection
