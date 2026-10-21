@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

{{-- Header --}}
<div class="relative overflow-hidden bg-gradient-to-br from-blue-950 via-blue-900 to-indigo-950 rounded-2xl p-4 sm:p-6 md:p-8 text-white shadow-sm mb-6 md:mb-8 border border-amber-400/20">
    {{-- decorative cross watermark --}}
    <svg class="absolute -right-4 -top-4 w-20 h-20 sm:-right-6 sm:-top-6 sm:w-36 sm:h-36 text-white/5 rotate-12" viewBox="0 0 24 24" fill="currentColor">
        <path d="M10 2h4v5h6v4h-6v11h-4v-11h-6v-4h6z"/>
    </svg>

    <div class="relative flex items-center gap-3 sm:gap-4">
        <img src="{{ asset('img/LOGO PAROKI-WARNA.png') }}" alt="Logo Paroki"
             class="w-10 h-10 sm:w-14 sm:h-14 object-contain shrink-0 bg-white/10 ring-1 ring-amber-400/40 rounded-xl p-1.5">
        <div class="min-w-0">
            <div class="hidden sm:flex items-center gap-2 mb-1">
                <span class="h-px w-5 bg-amber-400/70"></span>
                <p class="text-[11px] font-semibold uppercase tracking-widest text-amber-300">Perjalanan Iman</p>
            </div>
            <h1 class="text-base sm:text-xl font-bold text-white truncate">Selamat datang, {{ auth()->user()->name }}</h1>
            <p class="text-xs sm:text-sm text-blue-200 mt-0.5">Paroki Maria Marganingsih Kalasan</p>
        </div>
    </div>

    <p class="relative hidden sm:block mt-5 pt-4 border-t border-white/10 text-sm text-blue-100 italic">
        “Didiklah orang muda menurut jalan yang patut baginya, maka pada masa tuanya pun ia tidak akan menyimpang dari jalan itu.”
        <span class="block not-italic text-xs text-blue-300 mt-1">— Amsal 22:6</span>
    </p>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-11 h-11 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium">Kelas Aktif</p>
            <p class="text-2xl font-bold text-gray-900">{{ $batches->count() }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-11 h-11 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium">Tugas Menunggu</p>
            <p class="text-2xl font-bold {{ $pendingAssignments > 0 ? 'text-amber-600' : 'text-gray-900' }}">{{ $pendingAssignments }}</p>
        </div>
    </div>
</div>

{{-- Active test banner --}}
@if($activeTests > 0)
<div class="flex items-center gap-3 bg-blue-50 border border-blue-200 text-blue-800 rounded-2xl px-5 py-4 mb-6">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
    </svg>
    <div class="flex-1 text-sm">
        Ada <strong>{{ $activeTests }}</strong> test aktif yang belum dikerjakan.
    </div>
    <a href="{{ route('peserta.test.index') }}"
       class="shrink-0 text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg transition-colors">
        Kerjakan &rarr;
    </a>
</div>
@endif

{{-- Kelas --}}
<h2 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>
    </svg>
    Kelas Saya
</h2>

<div class="space-y-3">
@forelse($batches as $batch)
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center justify-between gap-4">
        <div>
            <p class="font-semibold text-gray-800">{{ $batch->name }}</p>
            <p class="text-sm text-gray-500">{{ $batch->program->name }}</p>
            @if($batch->start_date)
                <p class="text-xs text-gray-400 mt-1">
                    {{ $batch->start_date->format('d M Y') }}
                    @if($batch->end_date) — {{ $batch->end_date->format('d M Y') }} @endif
                </p>
            @endif
        </div>
        <span class="shrink-0 text-xs font-semibold bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full">Aktif</span>
    </div>
@empty
    <div class="bg-white rounded-2xl p-10 text-center shadow-sm border border-gray-100">
        <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>
            </svg>
        </div>
        <p class="text-gray-400 text-sm mb-3">Belum terdaftar di kelas manapun.</p>
        <a href="{{ route('peserta.kelas.index') }}"
           class="text-sm font-medium text-blue-600 hover:text-blue-700">Lihat Kelas Tersedia &rarr;</a>
    </div>
@endforelse
</div>

@endsection
