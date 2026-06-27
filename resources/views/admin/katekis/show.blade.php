@extends('layouts.app')

@section('title', 'Profil Katekis')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.katekis.index') }}" class="text-sm text-gray-500 hover:text-blue-600">&larr; Kembali ke Daftar Katekis</a>
</div>

{{-- Identitas --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="flex items-start justify-between gap-4 flex-wrap">
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 bg-violet-100 rounded-2xl flex items-center justify-center shrink-0">
                <span class="text-xl font-bold text-violet-700 uppercase">{{ substr($katekis->name, 0, 1) }}</span>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <h1 class="text-xl font-bold text-gray-900">{{ $katekis->name }}</h1>
                    @if($katekis->is_active)
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700">Akun Aktif</span>
                    @else
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-gray-100 text-gray-500">Akun Nonaktif</span>
                    @endif
                </div>
                <p class="text-sm text-gray-500 mt-1">{{ $katekis->email }}</p>
                @if($katekis->phone)
                    <p class="text-sm text-gray-500">{{ $katekis->phone }}</p>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-1.5 shrink-0">
            <a href="{{ route('admin.katekis.edit', $katekis) }}"
               class="text-xs font-medium bg-blue-50 text-blue-700 hover:bg-blue-100 px-2.5 py-1.5 rounded-lg transition-colors">
                Edit
            </a>
            @if($katekis->id !== auth()->id())
            <form method="POST" action="{{ route('admin.katekis.toggle-active', $katekis) }}">
                @csrf @method('PATCH')
                <button type="submit"
                        class="text-xs font-medium px-2.5 py-1.5 rounded-lg transition-colors
                               {{ $katekis->is_active
                                  ? 'bg-amber-50 text-amber-700 hover:bg-amber-100'
                                  : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                    {{ $katekis->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
            </form>
            @endif
            <form method="POST" action="{{ route('admin.katekis.reset-password', $katekis) }}"
                  onsubmit="return confirm('Reset password {{ $katekis->name }} ke default?')">
                @csrf @method('PATCH')
                <button type="submit"
                        class="text-xs font-medium bg-gray-50 text-gray-500 hover:bg-gray-100 px-2.5 py-1.5 rounded-lg transition-colors">
                    Reset PW
                </button>
            </form>
        </div>
    </div>

    <div class="border-t border-gray-100 mt-5 pt-5">
        <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Bidang</h2>
        @forelse($katekis->programs as $program)
            <span class="inline-block text-xs font-medium bg-violet-100 text-violet-700 px-2.5 py-1 rounded-full mr-1 mb-1">{{ $program->name }}</span>
        @empty
            <p class="text-sm text-gray-400">Belum ditentukan bidang katekese.</p>
        @endforelse
    </div>

    <div class="border-t border-gray-100 mt-5 pt-5">
        @if($katekis->profile)
            <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Data Pribadi</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <p class="text-xs text-gray-400">Wilayah</p>
                    <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $katekis->profile->wilayah ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Lingkungan</p>
                    <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $katekis->profile->lingkungan ?: '-' }}</p>
                </div>
                <div class="sm:col-span-2 lg:col-span-3">
                    <p class="text-xs text-gray-400">Alamat</p>
                    <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $katekis->profile->alamat ?: '-' }}</p>
                </div>
            </div>
        @else
            <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl px-4 py-3 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0 text-amber-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                </svg>
                Katekis ini belum melengkapi data profil.
            </div>
        @endif
    </div>
</div>

{{-- Kelas yang Diampu --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-700">Kelas yang Diampu</h2>
    </div>
    <div class="overflow-x-auto">
    <table class="w-full min-w-[560px] text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3 text-gray-500 font-semibold text-xs uppercase tracking-wide">Kelas</th>
                <th class="text-left px-5 py-3 text-gray-500 font-semibold text-xs uppercase tracking-wide">Angkatan</th>
                <th class="text-left px-5 py-3 text-gray-500 font-semibold text-xs uppercase tracking-wide">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($katekis->batchesAsKatekis as $batch)
            @php
                $statusLabel = match($batch->status) {
                    'active'    => 'Aktif',
                    'completed' => 'Selesai',
                    default     => 'Arsip',
                };
                $statusColor = match($batch->status) {
                    'active'    => 'bg-blue-100 text-blue-700',
                    'completed' => 'bg-emerald-100 text-emerald-700',
                    default     => 'bg-gray-100 text-gray-500',
                };
            @endphp
            <tr class="hover:bg-gray-50/80 transition-colors">
                <td class="px-5 py-3.5 text-gray-600">{{ $batch->program->name }}</td>
                <td class="px-5 py-3.5 font-medium text-gray-800">
                    <a href="{{ route('admin.batches.show', $batch) }}" class="hover:text-blue-600 transition-colors">{{ $batch->name }}</a>
                </td>
                <td class="px-5 py-3.5">
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusColor }}">{{ $statusLabel }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="px-5 py-12 text-center text-gray-400">Katekis ini belum ditugaskan ke kelas manapun.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection
