@extends('layouts.app')
@section('title', 'Dashboard')
@section('header-subtitle')Selamat datang, {{ auth()->user()->name }}@endsection
@section('content')

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
            </svg>
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-xs text-gray-500 font-medium">Total Siswa</p>
            <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ $stats['total_peserta'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-violet-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>
            </svg>
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-xs text-gray-500 font-medium">Total Katekis</p>
            <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ $stats['total_katekis'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium">Program Aktif</p>
            <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ $stats['total_program'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium">Angkatan Aktif</p>
            <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ $stats['total_angkatan'] }}</p>
        </div>
    </div>
</div>

{{-- Grafik --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-8">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <h2 class="font-semibold text-gray-700 mb-4">Peserta per Angkatan</h2>
        <div class="h-64">
            <canvas id="chartPesertaPerProgram"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <h2 class="font-semibold text-gray-700 mb-4">Katekis per Bidang</h2>
        <div class="h-64">
            <canvas id="chartKatekisPerBidang"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 lg:col-span-2">
        <h2 class="font-semibold text-gray-700 mb-4">Kelulusan per Angkatan</h2>
        <div class="h-64">
            <canvas id="chartLulusPerProgram"></canvas>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const palette = ['#2563eb', '#7c3aed', '#059669', '#d97706', '#dc2626', '#0891b2'];

    function withColors(datasets) {
        return datasets.map((ds, i) => ({
            ...ds,
            backgroundColor: palette[i % palette.length],
            borderRadius: 4,
        }));
    }

    new Chart(document.getElementById('chartPesertaPerProgram'), {
        type: 'bar',
        data: {
            labels: @json($pesertaPerProgramTahun['labels']),
            datasets: withColors(@json($pesertaPerProgramTahun['datasets'])),
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
            plugins: { legend: { position: 'bottom' } },
        },
    });

    new Chart(document.getElementById('chartKatekisPerBidang'), {
        type: 'bar',
        data: {
            labels: @json($katekisPerBidang->pluck('label')),
            datasets: [{
                label: 'Katekis',
                data: @json($katekisPerBidang->pluck('total')),
                backgroundColor: palette[1],
                borderRadius: 4,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            scales: { x: { beginAtZero: true, ticks: { precision: 0 } } },
            plugins: { legend: { display: false } },
        },
    });

    new Chart(document.getElementById('chartLulusPerProgram'), {
        type: 'bar',
        data: {
            labels: @json($lulusPerProgramTahun['labels']),
            datasets: withColors(@json($lulusPerProgramTahun['datasets'])),
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
            plugins: { legend: { position: 'bottom' } },
        },
    });
});
</script>

@endsection
