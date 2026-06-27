@extends('layouts.app')
@section('title', 'Test')
@section('content')
<div class="mb-6 flex items-start justify-between flex-wrap gap-y-3">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Test</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola test dan kuis per kelas</p>
    </div>
    <a href="{{ route('admin.tests.create') }}"
       class="shrink-0 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors shadow-sm">
        + Buat Test
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
        <a href="{{ route('admin.tests.index') }}" class="text-sm text-gray-400 hover:text-red-500 px-3 py-2 transition-colors">Reset</a>
    @endif
</form>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full min-w-[640px] text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wide">Judul</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wide">Kelas</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wide">Soal</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wide">Durasi</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wide">Status</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wide">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($tests as $test)
            <tr class="hover:bg-gray-50/80 transition-colors">
                <td class="px-5 py-3.5 font-medium text-gray-800">{{ $test->title }}</td>
                <td class="px-5 py-3.5 text-gray-500">{{ $test->batch->name }}</td>
                <td class="px-5 py-3.5 text-gray-500">{{ $test->questions_count }} soal</td>
                <td class="px-5 py-3.5 text-gray-500">{{ $test->duration_minutes ? $test->duration_minutes . ' menit' : 'Tanpa batas' }}</td>
                <td class="px-5 py-3.5">
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                 {{ $test->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $test->is_active ? 'Aktif' : 'Draft' }}
                    </span>
                </td>
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('admin.tests.show', $test) }}"
                           class="text-xs font-medium bg-violet-50 text-violet-700 hover:bg-violet-100 px-2.5 py-1 rounded-lg transition-colors">
                            Kelola Soal
                        </a>
                        <form method="POST" action="{{ route('admin.tests.toggle-active', $test) }}">
                            @csrf @method('PATCH')
                            <button class="text-xs font-medium px-2.5 py-1 rounded-lg transition-colors
                                           {{ $test->is_active
                                              ? 'bg-amber-50 text-amber-700 hover:bg-amber-100'
                                              : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                                {{ $test->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.tests.destroy', $test) }}"
                              onsubmit="return confirm('Hapus test ini?')">
                            @csrf @method('DELETE')
                            <button class="text-xs font-medium bg-red-50 text-red-600 hover:bg-red-100 px-2.5 py-1 rounded-lg transition-colors">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">Belum ada test.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if($tests->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">{{ $tests->links() }}</div>
    @endif
</div>
@endsection
