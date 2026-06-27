@extends('layouts.app')

@section('title', 'Daftar Katekis')

@section('content')
<div class="mb-6 flex items-start justify-between flex-wrap gap-y-3">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Daftar Katekis</h1>
        <p class="text-gray-500 text-sm mt-1">Katekis yang melayani dalam program katekese</p>
    </div>
    <a href="{{ route('admin.katekis.create') }}"
       class="shrink-0 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors shadow-sm">
        + Tambah Katekis
    </a>
</div>

{{-- Filter --}}
<form method="GET" class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6 flex gap-3 flex-wrap">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Cari nama katekis..."
           class="flex-1 min-w-48 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">

    <select name="program_id" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
        <option value="">Semua Bidang</option>
        @foreach($programs as $program)
            <option value="{{ $program->id }}" @selected(request('program_id') == $program->id)>{{ $program->name }}</option>
        @endforeach
    </select>

    <select name="status" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
        <option value="">Semua Status</option>
        <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
        <option value="nonaktif" @selected(request('status') === 'nonaktif')>Nonaktif</option>
    </select>

    <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-4 py-2 rounded-xl transition-colors">Cari</button>
    @if(request()->anyFilled(['search', 'program_id', 'status']))
        <a href="{{ route('admin.katekis.index') }}" class="text-sm text-gray-400 hover:text-red-500 px-3 py-2 transition-colors">Reset</a>
    @endif
</form>

{{-- Tabel --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full min-w-[680px] text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wide">Nama</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wide">Wilayah</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wide">Lingkungan</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wide">No HP</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wide">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($katekisList as $person)
            <tr class="hover:bg-gray-50/80 transition-colors">
                <td class="px-5 py-3.5">
                    <a href="{{ route('admin.katekis.show', $person) }}" class="font-medium text-gray-800 hover:text-blue-600 transition-colors">{{ $person->name }}</a>
                    <p class="text-xs text-gray-400">{{ $person->email }}</p>
                </td>
                <td class="px-5 py-3.5 text-gray-600">{{ $person->profile?->wilayah ?: '-' }}</td>
                <td class="px-5 py-3.5 text-gray-600">{{ $person->profile?->lingkungan ?: '-' }}</td>
                <td class="px-5 py-3.5 text-gray-600">{{ $person->phone ?: '-' }}</td>
                <td class="px-5 py-3.5">
                    <a href="{{ route('admin.katekis.edit', $person) }}"
                       class="text-xs font-medium bg-blue-50 text-blue-700 hover:bg-blue-100 px-2.5 py-1 rounded-lg transition-colors">
                        Edit
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-5 py-12 text-center text-gray-400">Tidak ada katekis ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if($katekisList->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $katekisList->links() }}
        </div>
    @endif
</div>
@endsection
