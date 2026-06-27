@extends('layouts.app')

@section('title', 'Profil Siswa')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.students.index') }}" class="text-sm text-gray-500 hover:text-blue-600">&larr; Kembali ke Daftar Siswa</a>
</div>

{{-- Identitas --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="flex items-start justify-between gap-4 flex-wrap">
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center shrink-0">
                <span class="text-xl font-bold text-blue-700 uppercase">{{ substr($student->name, 0, 1) }}</span>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <h1 class="text-xl font-bold text-gray-900">{{ $student->name }}</h1>
                    @if($student->is_active)
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700">Akun Aktif</span>
                    @else
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-gray-100 text-gray-500">Akun Nonaktif</span>
                    @endif
                </div>
                <p class="text-sm text-gray-500 mt-1">{{ $student->email }}</p>
                @if($student->phone)
                    <p class="text-sm text-gray-500">{{ $student->phone }}</p>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-1.5 shrink-0">
            <form method="POST" action="{{ route('admin.students.toggle-active', $student) }}">
                @csrf @method('PATCH')
                <button type="submit"
                        class="text-xs font-medium px-2.5 py-1.5 rounded-lg transition-colors
                               {{ $student->is_active
                                  ? 'bg-amber-50 text-amber-700 hover:bg-amber-100'
                                  : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                    {{ $student->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
            </form>
            <form method="POST" action="{{ route('admin.students.reset-password', $student) }}"
                  onsubmit="return confirm('Reset password {{ $student->name }} ke default?')">
                @csrf @method('PATCH')
                <button type="submit"
                        class="text-xs font-medium bg-gray-50 text-gray-500 hover:bg-gray-100 px-2.5 py-1.5 rounded-lg transition-colors">
                    Reset PW
                </button>
            </form>
        </div>
    </div>

    <div class="border-t border-gray-100 mt-5 pt-5">
        @if($student->profile)
            <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Data Pribadi</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <p class="text-xs text-gray-400">Nama Baptis</p>
                    <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $student->profile->nama_baptis ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Sekolah</p>
                    <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $student->profile->sekolah }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Kelas / Tingkat</p>
                    <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $student->profile->kelas }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Tanggal Lahir</p>
                    <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $student->profile->tanggal_lahir?->format('d M Y') ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Wilayah</p>
                    <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $student->profile->wilayah }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Lingkungan</p>
                    <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $student->profile->lingkungan }}</p>
                </div>
                <div class="sm:col-span-2 lg:col-span-3">
                    <p class="text-xs text-gray-400">Alamat</p>
                    <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $student->profile->alamat ?: '-' }}</p>
                </div>

                <div class="col-span-full border-t border-gray-100 pt-4 mt-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Data Orang Tua</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Nama Ayah</p>
                    <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $student->profile->nama_ayah ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Nama Ibu</p>
                    <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $student->profile->nama_ibu ?: '-' }}</p>
                </div>

                <div class="col-span-full border-t border-gray-100 pt-4 mt-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Data Sakramen</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Gereja Baptis</p>
                    <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $student->profile->gereja_baptis ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Nomor Surat Baptis</p>
                    <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $student->profile->nomor_buku_baptis ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Gereja Komuni Pertama</p>
                    <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $student->profile->gereja_komuni_pertama ?: '-' }}</p>
                </div>
            </div>
        @else
            <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl px-4 py-3 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0 text-amber-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                </svg>
                Siswa ini belum melengkapi data profil.
            </div>
        @endif
    </div>
</div>

{{-- Riwayat Kelas --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-700">Riwayat Kelas</h2>
        {{-- TODO: tambahkan link "Lihat Raport" per kelas di sini setelah halaman Raport dibangun --}}
    </div>
    <div class="overflow-x-auto">
    <table class="w-full min-w-[600px] text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3 text-gray-500 font-semibold text-xs uppercase tracking-wide">Kelas</th>
                <th class="text-left px-5 py-3 text-gray-500 font-semibold text-xs uppercase tracking-wide">Angkatan</th>
                <th class="text-left px-5 py-3 text-gray-500 font-semibold text-xs uppercase tracking-wide">Tgl Bergabung</th>
                <th class="text-left px-5 py-3 text-gray-500 font-semibold text-xs uppercase tracking-wide">Pendaftaran</th>
                <th class="text-left px-5 py-3 text-gray-500 font-semibold text-xs uppercase tracking-wide">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($enrollments as $batch)
            @php
                $enrollColor = match($batch->pivot->status) {
                    'approved' => 'bg-emerald-100 text-emerald-700',
                    'rejected' => 'bg-red-100 text-red-700',
                    default    => 'bg-amber-100 text-amber-700',
                };
                $enrollLabel = match($batch->pivot->status) {
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    default    => 'Menunggu',
                };
                $statusLabel = match($batch->status) {
                    'active'    => 'Aktif',
                    'completed' => 'Lulus',
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
                <td class="px-5 py-3.5 font-medium text-gray-800">{{ $batch->name }}</td>
                <td class="px-5 py-3.5 text-gray-500">{{ $batch->pivot->joined_at ? \Illuminate\Support\Carbon::parse($batch->pivot->joined_at)->format('d M Y') : '-' }}</td>
                <td class="px-5 py-3.5">
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $enrollColor }}">{{ $enrollLabel }}</span>
                </td>
                <td class="px-5 py-3.5">
                    @if($batch->pivot->status === 'approved')
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusColor }}">{{ $statusLabel }}</span>
                    @else
                        <span class="text-xs text-gray-400">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-5 py-12 text-center text-gray-400">Siswa ini belum pernah mendaftar kelas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection
