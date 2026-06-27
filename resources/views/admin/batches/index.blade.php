@extends('layouts.app')

@section('title', request()->boolean('mine') ? 'Kelas Saya' : 'Kelola Kelas')

@section('content')
<div class="mb-6 flex items-start justify-between flex-wrap gap-y-3">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ request()->boolean('mine') ? 'Kelas Saya' : 'Kelola Kelas' }}</h1>
        <p class="text-gray-500 text-sm mt-1">
            {{ request()->boolean('mine')
                ? 'Kelas yang sedang maupun sudah selesai Anda ampu'
                : 'Daftar semua kelas katekese (Baptis, Komuni, Krisma)' }}
        </p>
    </div>
    <a href="{{ route('admin.batches.create') }}"
       class="shrink-0 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors shadow-sm">
        + Buat Kelas
    </a>
</div>

{{-- Filter --}}
<form method="GET" class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6 flex gap-3 flex-wrap items-center">
    @if(request()->boolean('mine'))
        <input type="hidden" name="mine" value="1">
    @endif
    @if(request('program_id'))
        <input type="hidden" name="program_id" value="{{ request('program_id') }}">
    @endif
    <select name="year" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
        <option value="">Semua Tahun</option>
        @foreach($yearsQuery as $year)
            <option value="{{ $year }}" @selected(request('year') == $year)>{{ $year }}</option>
        @endforeach
    </select>
    <select name="status" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
        <option value="">Semua Status</option>
        <option value="active"    @selected(request('status') === 'active')>Aktif</option>
        <option value="completed" @selected(request('status') === 'completed')>Selesai</option>
        <option value="archived"  @selected(request('status') === 'archived')>Arsip</option>
    </select>
    @if(request('year') || request('status'))
        <a href="{{ route('admin.batches.index', array_filter(['mine' => request()->boolean('mine') ?: null, 'program_id' => request('program_id') ?: null])) }}"
           class="text-sm text-gray-400 hover:text-red-500 px-3 py-2 transition-colors">Reset</a>
    @endif
</form>

{{-- Tabel --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full min-w-[700px] text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wide">Nama Kelas</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wide">Program</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wide">Periode</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wide">Katekis</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wide">Peserta</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wide">Status</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wide">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($batches as $batch)
            <tr class="transition-colors {{ $batch->status === 'completed' ? 'bg-emerald-100 hover:bg-emerald-200/70' : ($batch->status === 'archived' ? 'bg-gray-50/60 hover:bg-gray-100/60' : 'hover:bg-gray-50/80') }}">
                <td class="px-5 py-3.5">
                    <a href="{{ route('admin.batches.show', $batch) }}"
                       class="font-semibold text-blue-600 hover:text-blue-700">{{ $batch->name }}</a>
                </td>
                <td class="px-5 py-3.5 text-gray-500">{{ $batch->program->name }}</td>
                <td class="px-5 py-3.5 text-gray-400 text-xs">
                    @if($batch->start_date)
                        {{ $batch->start_date->format('d M Y') }}
                        @if($batch->end_date) — {{ $batch->end_date->format('d M Y') }} @endif
                    @else
                        -
                    @endif
                </td>
                <td class="px-5 py-3.5 text-gray-500">{{ $batch->katekis_count }}</td>
                <td class="px-5 py-3.5 text-gray-500">{{ $batch->peserta_count }}</td>
                <td class="px-5 py-3.5">
                    @php
                        $statusColor = match($batch->status) {
                            'active'    => 'bg-emerald-100 text-emerald-700',
                            'completed' => 'bg-blue-100 text-blue-700',
                            default     => 'bg-gray-100 text-gray-500',
                        };
                        $statusLabel = match($batch->status) {
                            'active'    => 'Aktif',
                            'completed' => 'Selesai',
                            default     => 'Arsip',
                        };
                    @endphp
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusColor }}">{{ $statusLabel }}</span>
                </td>
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('admin.batches.show', $batch) }}"
                           class="text-xs font-medium bg-violet-50 text-violet-700 hover:bg-violet-100 px-2.5 py-1 rounded-lg transition-colors">
                            Kelola
                        </a>
                        <a href="{{ route('admin.batches.edit', $batch) }}"
                           class="text-xs font-medium bg-blue-50 text-blue-700 hover:bg-blue-100 px-2.5 py-1 rounded-lg transition-colors">
                            Edit
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-5 py-12 text-center text-gray-400">Belum ada kelas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if($batches->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $batches->links() }}
        </div>
    @endif
</div>
@endsection
