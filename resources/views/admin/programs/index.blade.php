@extends('layouts.app')

@section('title', 'Kelola Program')

@section('content')
<div class="mb-6 flex items-start justify-between flex-wrap gap-y-3">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Kelola Program</h1>
        <p class="text-gray-500 text-sm mt-1">Program katekese (Baptis, Krisma, dll.)</p>
    </div>
    <a href="{{ route('admin.programs.create') }}"
       class="shrink-0 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        + Tambah Program
    </a>
</div>

@php
    $programIcons = function(string $name): string {
        $name = strtolower($name);
        if (str_contains($name, 'baptis'))  return '🕊️';
        if (str_contains($name, 'komuni'))  return '🍷';
        if (str_contains($name, 'krisma'))  return '🙏';
        return '✝️';
    };

    $themes = [
        ['card' => 'bg-blue-50',    'border' => 'border-blue-200',    'text' => 'text-blue-900',    'btn' => 'bg-blue-600 hover:bg-blue-700'],
        ['card' => 'bg-violet-50', 'border' => 'border-violet-200',  'text' => 'text-violet-900',  'btn' => 'bg-violet-600 hover:bg-violet-700'],
        ['card' => 'bg-amber-50',  'border' => 'border-amber-200',   'text' => 'text-amber-900',   'btn' => 'bg-amber-600 hover:bg-amber-700'],
        ['card' => 'bg-emerald-50','border' => 'border-emerald-200', 'text' => 'text-emerald-900', 'btn' => 'bg-emerald-600 hover:bg-emerald-700'],
        ['card' => 'bg-rose-50',   'border' => 'border-rose-200',    'text' => 'text-rose-900',    'btn' => 'bg-rose-600 hover:bg-rose-700'],
        ['card' => 'bg-cyan-50',   'border' => 'border-cyan-200',    'text' => 'text-cyan-900',    'btn' => 'bg-cyan-600 hover:bg-cyan-700'],
    ];
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
    @forelse($programs as $program)
    @php $theme = $themes[$loop->index % count($themes)]; @endphp
    <div class="rounded-2xl border {{ $theme['card'] }} {{ $theme['border'] }} flex flex-col">
        <div class="p-5 flex flex-col gap-3 flex-1">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <span class="text-3xl leading-none">{{ $programIcons($program->name) }}</span>
                    <h2 class="text-xl font-bold {{ $theme['text'] }} leading-tight mt-2">{{ $program->name }}</h2>
                </div>
                @if($program->status === 'active')
                    <span class="shrink-0 text-xs font-medium px-2 py-1 rounded-full bg-white/70 {{ $theme['text'] }}">Aktif</span>
                @else
                    <span class="shrink-0 text-xs font-medium px-2 py-1 rounded-full bg-white/70 text-gray-500">Arsip</span>
                @endif
            </div>

            @if($program->description)
                <p class="text-sm {{ $theme['text'] }} opacity-70 leading-relaxed">{{ $program->description }}</p>
            @endif

            <div class="mt-auto pt-3 flex flex-col gap-2">
                <a href="{{ route('admin.batches.index', ['program_id' => $program->id]) }}"
                   class="block text-center text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors {{ $theme['btn'] }}">
                    Lihat Kelas &rarr;
                </a>
                <div class="flex items-center justify-center gap-3 pt-1">
                    <a href="{{ route('admin.programs.edit', $program) }}"
                       class="text-xs {{ $theme['text'] }} opacity-70 hover:opacity-100 transition-opacity">Edit</a>
                    <span class="{{ $theme['text'] }} opacity-30">&middot;</span>
                    <form method="POST" action="{{ route('admin.programs.toggle-status', $program) }}">
                        @csrf @method('PATCH')
                        <button type="submit"
                                class="text-xs {{ $theme['text'] }} opacity-70 hover:opacity-100 transition-opacity">
                            {{ $program->status === 'active' ? 'Arsipkan' : 'Aktifkan' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-3 bg-white rounded-xl p-10 text-center text-gray-400 border border-gray-100">
        Belum ada program. <a href="{{ route('admin.programs.create') }}" class="text-blue-500 hover:underline">Tambah sekarang</a>.
    </div>
    @endforelse
</div>
@endsection
