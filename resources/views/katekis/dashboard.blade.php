@extends('layouts.app')

@section('title', 'Dashboard Katekis')

@section('header-subtitle')Selamat datang, {{ auth()->user()->name }}@endsection

@section('content')
<div class="mb-6">
    <h2 class="font-semibold text-gray-700 mb-3">Angkatan Saya</h2>
    @forelse($batches as $batch)
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 mb-3">
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-medium text-gray-800">{{ $batch->name }}</p>
                    <p class="text-sm text-gray-500">{{ $batch->program->name }}</p>
                </div>
                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">Aktif</span>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl p-8 text-center text-gray-400 shadow-sm border border-gray-100">
            Belum ada angkatan yang ditugaskan.
        </div>
    @endforelse
</div>
@endsection
