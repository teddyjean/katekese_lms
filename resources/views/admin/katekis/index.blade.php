@extends('layouts.app')

@section('title', 'Daftar Katekis')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Daftar Katekis</h1>
    <p class="text-gray-500 text-sm mt-1">Katekis yang melayani dalam program katekese</p>
</div>

{{-- Filter --}}
<form method="GET" class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6 flex gap-3 flex-wrap">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Cari nama katekis..."
           class="w-full sm:flex-1 sm:min-w-48 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">

    <select name="program_id" class="flex-1 min-w-[140px] sm:flex-initial border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
        <option value="">Semua Bidang</option>
        @foreach($programs as $program)
            <option value="{{ $program->id }}" @selected(request('program_id') == $program->id)>{{ $program->name }}</option>
        @endforeach
    </select>

    <select name="status" class="flex-1 min-w-[140px] sm:flex-initial border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
        <option value="aktif" @selected(request('status', 'aktif') === 'aktif')>Aktif</option>
        <option value="nonaktif" @selected(request('status') === 'nonaktif')>Nonaktif</option>
        <option value="semua" @selected(request('status') === 'semua')>Semua Status</option>
    </select>

    <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-4 py-2 rounded-xl transition-colors">Cari</button>
    @if(request()->anyFilled(['search', 'program_id']) || request('status', 'aktif') !== 'aktif')
        <a href="{{ route('admin.katekis.index') }}" class="text-sm text-gray-400 hover:text-red-500 px-3 py-2 transition-colors">Reset</a>
    @endif
</form>

{{-- Mobile: card list --}}
<div class="sm:hidden space-y-3 mb-6">
    @forelse($katekisList as $person)
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-start justify-between gap-2 mb-2">
                <div class="min-w-0">
                    <a href="{{ route('admin.katekis.show', $person) }}" class="font-medium text-gray-800 hover:text-blue-600 transition-colors break-words">{{ $person->name }}</a>
                    <p class="text-xs text-gray-400 truncate">{{ $person->email }}</p>
                </div>
                @can('manageKatekis', $person)
                <a href="{{ route('admin.katekis.edit', $person) }}"
                   class="shrink-0 text-xs font-medium bg-blue-50 text-blue-700 hover:bg-blue-100 px-2.5 py-1 rounded-lg transition-colors">
                    Edit
                </a>
                @else
                <a href="{{ route('admin.katekis.show', $person) }}"
                   class="shrink-0 text-xs font-medium bg-gray-50 text-gray-700 hover:bg-gray-100 px-2.5 py-1 rounded-lg transition-colors">
                    Lihat
                </a>
                @endcan
            </div>
            <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-500">
                <span>Wilayah: {{ $person->profile?->wilayah ?: '-' }}</span>
                <span>Lingkungan: {{ $person->profile?->lingkungan ?: '-' }}</span>
                <span>HP: {{ $person->phone ?: '-' }}</span>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl p-8 text-center text-gray-400 shadow-sm border border-gray-100">Tidak ada katekis ditemukan.</div>
    @endforelse
</div>

{{-- Desktop: table --}}
<div class="hidden sm:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
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
                    @can('manageKatekis', $person)
                    <a href="{{ route('admin.katekis.edit', $person) }}"
                       class="text-xs font-medium bg-blue-50 text-blue-700 hover:bg-blue-100 px-2.5 py-1 rounded-lg transition-colors">
                        Edit
                    </a>
                    @else
                    <a href="{{ route('admin.katekis.show', $person) }}"
                       class="text-xs font-medium bg-gray-50 text-gray-700 hover:bg-gray-100 px-2.5 py-1 rounded-lg transition-colors">
                        Lihat
                    </a>
                    @endcan
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
</div>

@if($katekisList->hasPages())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-5 py-4">
        {{ $katekisList->links() }}
    </div>
@endif
@endsection
