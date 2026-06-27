@extends('layouts.app')
@section('title', $batch->name)

@section('content')

{{-- Header --}}
<div class="print:hidden mb-5 flex items-center justify-between flex-wrap gap-3">
    <div>
        <a href="{{ route('admin.batches.index') }}" class="text-sm text-gray-500 hover:text-blue-600">&larr; Kembali ke Kelas</a>
        <h1 class="text-xl font-bold text-gray-800 mt-1">{{ $batch->name }}</h1>
        <p class="text-sm text-gray-500">{{ $batch->program->name }}
            &middot; {{ $batch->katekis->pluck('name')->join(', ') ?: '-' }}
            &middot; <span class="capitalize">{{ $batch->status }}</span>
        </p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.batches.edit', $batch) }}"
           class="text-sm font-medium bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-xl transition-colors shadow-sm">
            Edit Kelas
        </a>
    </div>
</div>

@if(session('success'))
<div class="print:hidden mb-4 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl">
    {{ session('success') }}
</div>
@endif

{{-- Tab Nav --}}
<div class="print:hidden border-b border-gray-200 mb-6">
    <nav class="-mb-px flex gap-1 overflow-x-auto" id="tab-nav">
        @php
            $tabs = [
                'peserta'    => ['label' => 'Peserta', 'count' => $peserta->count()],
                'materi'     => ['label' => 'Materi', 'count' => $materials->count()],
                'tugas'      => ['label' => 'Tugas', 'count' => $assignments->count()],
                'test'       => ['label' => 'Test', 'count' => $tests->count()],
                'pertemuan'  => ['label' => 'Pertemuan', 'count' => $meetings->count()],
                'dokumen'    => ['label' => 'Dokumen', 'count' => null],
            ];
        @endphp
        @foreach($tabs as $key => $tab)
        <button type="button" data-tab="{{ $key }}"
                class="tab-btn whitespace-nowrap px-4 py-2.5 text-sm font-medium border-b-2 transition-colors
                       border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
            {{ $tab['label'] }}
            @if($tab['count'] !== null)
                <span class="ml-1 text-xs bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded-full">{{ $tab['count'] }}</span>
            @endif
        </button>
        @endforeach
    </nav>
</div>

{{-- ── TAB: PESERTA ─────────────────────────────────────────────────────── --}}
<div id="tab-peserta" class="tab-panel print:hidden">

    {{-- Pending --}}
    @if($pending->count())
    <div class="mb-6 bg-amber-50 border border-amber-200 rounded-xl p-4">
        <h3 class="text-sm font-semibold text-amber-700 mb-3">Menunggu Persetujuan ({{ $pending->count() }})</h3>
        <div class="space-y-2">
            @foreach($pending as $p)
            <div class="flex items-center justify-between gap-3 bg-white rounded-lg px-4 py-2 border border-amber-100">
                <span class="text-sm font-medium text-gray-800">{{ $p->name }}</span>
                <div class="flex gap-2">
                    <form method="POST" action="{{ route('admin.batches.peserta.approve', [$batch, $p]) }}">
                        @csrf
                        <button class="text-xs bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg">Terima</button>
                    </form>
                    <form method="POST" action="{{ route('admin.batches.peserta.reject', [$batch, $p]) }}">
                        @csrf
                        <button class="text-xs bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg">Tolak</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Peserta List --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-4">
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700">Peserta Terdaftar</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-4 py-2.5 font-medium text-gray-600 w-8">No</th>
                    <th class="text-left px-4 py-2.5 font-medium text-gray-600">Nama</th>
                    <th class="text-left px-4 py-2.5 font-medium text-gray-600 hidden sm:table-cell">Wilayah</th>
                    <th class="text-left px-4 py-2.5 font-medium text-gray-600 hidden sm:table-cell">Lingkungan</th>
                    <th class="text-left px-4 py-2.5 font-medium text-gray-600">Kelulusan</th>
                    <th class="px-4 py-2.5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($peserta as $i => $p)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2.5 text-gray-400">{{ $i + 1 }}</td>
                    <td class="px-4 py-2.5 font-medium text-gray-800">{{ $p->name }}</td>
                    <td class="px-4 py-2.5 text-gray-500 hidden sm:table-cell">{{ $p->profile?->wilayah ?? '-' }}</td>
                    <td class="px-4 py-2.5 text-gray-500 hidden sm:table-cell">{{ $p->profile?->lingkungan ?? '-' }}</td>
                    <td class="px-4 py-2.5">
                        <form method="POST" action="{{ route('admin.batches.peserta.kelulusan', [$batch, $p]) }}">
                            @csrf @method('PATCH')
                            <select name="lulus" onchange="this.form.submit()"
                                    class="text-xs border border-gray-200 rounded-lg px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-300
                                        {{ $p->pivot->lulus === true ? 'bg-emerald-50 text-emerald-700' : ($p->pivot->lulus === false ? 'bg-red-50 text-red-700' : 'text-gray-400') }}">
                                <option value="" @selected($p->pivot->lulus === null)>— Belum</option>
                                <option value="1" @selected($p->pivot->lulus === true)>Lulus</option>
                                <option value="0" @selected($p->pivot->lulus === false)>Tidak Lulus</option>
                            </select>
                        </form>
                    </td>
                    <td class="px-4 py-2.5 text-right">
                        <form method="POST" action="{{ route('admin.batches.peserta.remove', [$batch, $p]) }}"
                              onsubmit="return confirm('Hapus {{ $p->name }} dari kelas ini?')">
                            @csrf @method('DELETE')
                            <button class="text-xs text-red-500 hover:text-red-700">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada peserta.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Tambah Peserta --}}
    @if($availablePeserta->count())
    <form method="POST" action="{{ route('admin.batches.peserta.assign', $batch) }}" class="flex gap-2 items-center">
        @csrf
        <select name="user_id" class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            <option value="">-- Tambah Peserta --</option>
            @foreach($availablePeserta as $u)
                <option value="{{ $u->id }}">{{ $u->name }}</option>
            @endforeach
        </select>
        <button class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg">Tambah</button>
    </form>
    @endif
</div>

{{-- ── TAB: MATERI ──────────────────────────────────────────────────────── --}}
<div id="tab-materi" class="tab-panel print:hidden hidden">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-sm font-semibold text-gray-700">Materi Kelas</h3>
        <a href="{{ route('admin.materials.create') }}?batch_id={{ $batch->id }}"
           class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">+ Tambah Materi</a>
    </div>
    <div class="space-y-3">
        @forelse($materials as $m)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <span class="text-xs bg-blue-50 text-blue-600 font-semibold px-2 py-1 rounded-lg w-8 text-center">{{ $m->order }}</span>
                <div>
                    <a href="{{ route('admin.materials.show', $m) }}" class="font-medium text-gray-800 hover:text-blue-600 text-sm">{{ $m->title }}</a>
                    @if($m->description)
                        <p class="text-xs text-gray-400 mt-0.5">{{ Str::limit($m->description, 80) }}</p>
                    @endif
                </div>
            </div>
            <div class="flex gap-2 shrink-0">
                <a href="{{ route('admin.materials.edit', $m) }}" class="text-xs text-gray-500 hover:text-blue-600">Edit</a>
                <form method="POST" action="{{ route('admin.materials.destroy', $m) }}"
                      onsubmit="return confirm('Hapus materi ini?')">
                    @csrf @method('DELETE')
                    <button class="text-xs text-red-500 hover:text-red-700">Hapus</button>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center py-10 text-gray-400 text-sm">Belum ada materi. Klik "+ Tambah Materi" untuk mulai.</div>
        @endforelse
    </div>
</div>

{{-- ── TAB: TUGAS ───────────────────────────────────────────────────────── --}}
<div id="tab-tugas" class="tab-panel print:hidden hidden">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-sm font-semibold text-gray-700">Tugas</h3>
        <a href="{{ route('admin.assignments.create') }}?batch_id={{ $batch->id }}"
           class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">+ Buat Tugas</a>
    </div>
    <div class="space-y-3">
        @forelse($assignments as $a)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3 flex items-center justify-between gap-3">
            <div>
                <a href="{{ route('admin.assignments.show', $a) }}" class="font-medium text-gray-800 hover:text-blue-600 text-sm">{{ $a->title }}</a>
                <p class="text-xs text-gray-400 mt-0.5">
                    Deadline: {{ $a->deadline ? $a->deadline->translatedFormat('d M Y') : '-' }}
                    &middot; Nilai max: {{ $a->max_score }}
                </p>
            </div>
            <div class="flex gap-2 shrink-0">
                <a href="{{ route('admin.assignments.show', $a) }}" class="text-xs text-gray-500 hover:text-blue-600">Nilai</a>
                <a href="{{ route('admin.assignments.edit', $a) }}" class="text-xs text-gray-500 hover:text-blue-600">Edit</a>
                <form method="POST" action="{{ route('admin.assignments.destroy', $a) }}"
                      onsubmit="return confirm('Hapus tugas ini?')">
                    @csrf @method('DELETE')
                    <button class="text-xs text-red-500 hover:text-red-700">Hapus</button>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center py-10 text-gray-400 text-sm">Belum ada tugas.</div>
        @endforelse
    </div>
</div>

{{-- ── TAB: TEST ────────────────────────────────────────────────────────── --}}
<div id="tab-test" class="tab-panel print:hidden hidden">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-sm font-semibold text-gray-700">Test / Kuis</h3>
        <a href="{{ route('admin.tests.create') }}?batch_id={{ $batch->id }}"
           class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">+ Buat Test</a>
    </div>
    <div class="space-y-3">
        @forelse($tests as $t)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3 flex items-center justify-between gap-3">
            <div>
                <a href="{{ route('admin.tests.show', $t) }}" class="font-medium text-gray-800 hover:text-blue-600 text-sm">{{ $t->title }}</a>
                <p class="text-xs text-gray-400 mt-0.5">
                    {{ $t->questions_count }} soal
                    @if($t->duration_minutes) &middot; {{ $t->duration_minutes }} menit @endif
                    &middot;
                    <span class="{{ $t->is_active ? 'text-emerald-600' : 'text-gray-400' }}">
                        {{ $t->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </p>
            </div>
            <div class="flex gap-2 shrink-0">
                <form method="POST" action="{{ route('admin.tests.toggle-active', $t) }}">
                    @csrf @method('PATCH')
                    <button class="text-xs {{ $t->is_active ? 'text-amber-500 hover:text-amber-700' : 'text-emerald-600 hover:text-emerald-700' }}">
                        {{ $t->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                </form>
                <a href="{{ route('admin.tests.edit', $t) }}" class="text-xs text-gray-500 hover:text-blue-600">Edit</a>
                <form method="POST" action="{{ route('admin.tests.destroy', $t) }}"
                      onsubmit="return confirm('Hapus test ini?')">
                    @csrf @method('DELETE')
                    <button class="text-xs text-red-500 hover:text-red-700">Hapus</button>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center py-10 text-gray-400 text-sm">Belum ada test.</div>
        @endforelse
    </div>
</div>

{{-- ── TAB: PERTEMUAN ───────────────────────────────────────────────────── --}}
<div id="tab-pertemuan" class="tab-panel print:hidden hidden">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Form Tambah Pertemuan --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Jadwalkan Pertemuan</h3>
            <form method="POST" action="{{ route('admin.meetings.store', $batch) }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" required
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Jam</label>
                    <input type="time" name="jam" required
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Lokasi</label>
                    <input type="text" name="location" placeholder="cth. Aula Paroki"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Materi Terkait</label>
                    <select name="material_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <option value="">-- Pilih Materi --</option>
                        @foreach($batchMaterials as $m)
                            <option value="{{ $m->id }}">{{ $m->title }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 rounded-lg">
                    Tambah Pertemuan
                </button>
            </form>
        </div>

        {{-- List Pertemuan --}}
        <div class="lg:col-span-2 space-y-3">
            @forelse($meetings as $meeting)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="font-medium text-gray-800 text-sm">{{ $meeting->title }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $meeting->scheduled_at->translatedFormat('d M Y, H:i') }}
                            @if($meeting->location) &middot; {{ $meeting->location }} @endif
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $meeting->attendances->count() }} presensi tercatat
                        </p>
                    </div>
                    <div class="flex gap-2 shrink-0">
                        <a href="{{ route('admin.meetings.attendance.edit', $meeting) }}"
                           class="text-xs text-blue-600 hover:text-blue-700">Presensi</a>
                        <form method="POST" action="{{ route('admin.meetings.destroy', $meeting) }}"
                              onsubmit="return confirm('Hapus pertemuan ini?')">
                            @csrf @method('DELETE')
                            <button class="text-xs text-red-500 hover:text-red-700">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-10 text-gray-400 text-sm">Belum ada jadwal pertemuan.</div>
            @endforelse
        </div>
    </div>
</div>

{{-- ── TAB: DOKUMEN ─────────────────────────────────────────────────────── --}}
<div id="tab-dokumen" class="tab-panel hidden">

    @if($batch->status === 'completed')
    <form method="POST" action="{{ route('admin.batches.update-document', $batch) }}"
          class="print:hidden mb-5 bg-amber-50 border border-amber-200 rounded-2xl p-4 flex flex-wrap gap-4 items-end text-sm">
        @csrf @method('PATCH')
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-medium text-amber-700 mb-1">Nama Romo Paroki</label>
            <input type="text" name="nama_romo" value="{{ $batch->nama_romo }}"
                   placeholder="cth. Rm. Yohanes Budi, Pr"
                   class="w-full border border-amber-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300 bg-white">
        </div>
        <div>
            <label class="block text-xs font-medium text-amber-700 mb-1">Tanggal Penerimaan Sakramen</label>
            <input type="date" name="tanggal_sakramen" value="{{ $batch->tanggal_sakramen?->format('Y-m-d') }}"
                   class="border border-amber-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300 bg-white">
        </div>
        <button type="submit"
                class="bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-5 py-2 rounded-xl transition-colors">
            Simpan
        </button>
    </form>
    @endif

    <div class="print:hidden mb-3 text-right">
        <button onclick="window.print()"
                class="text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl transition-colors shadow-sm">
            Cetak Dokumen
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 md:p-12 max-w-4xl mx-auto print:shadow-none print:border-none print:rounded-none print:p-0 print:max-w-none">
        <div class="flex items-center gap-5 pb-4 border-b-4 border-double border-gray-800 mb-6">
            <img src="{{ asset('img/LOGO PAROKI-WARNA.png') }}" alt="Logo Paroki" class="w-16 h-16 object-contain shrink-0">
            <div class="flex-1 text-center">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-500">Keuskupan Agung Semarang</p>
                <h1 class="text-xl font-bold uppercase text-gray-900 leading-tight">Paroki Maria Marganingsih Kalasan</h1>
                <p class="text-xs text-gray-500 mt-0.5">Program Katekese Sakramen Inisiasi</p>
            </div>
            <div class="w-16 shrink-0"></div>
        </div>

        <div class="text-center mb-8">
            <h2 class="text-base font-bold uppercase underline tracking-wide text-gray-900">
                Daftar Peserta Kelas {{ $batch->program->name }}
            </h2>
        </div>

        <table class="text-sm mb-8 w-auto">
            <tr><td class="pr-4 py-0.5 text-gray-500 w-40">Nama Kelas</td><td class="py-0.5 text-gray-800">: {{ $batch->name }}</td></tr>
            <tr><td class="pr-4 py-0.5 text-gray-500">Program</td><td class="py-0.5 text-gray-800">: Pendampingan {{ $batch->program->name }}</td></tr>
            <tr><td class="pr-4 py-0.5 text-gray-500">Katekis</td><td class="py-0.5 text-gray-800">: {{ $batch->katekis->pluck('name')->join(', ') ?: '-' }}</td></tr>
            <tr>
                <td class="pr-4 py-0.5 text-gray-500">Periode</td>
                <td class="py-0.5 text-gray-800">:
                    @if($batch->start_date)
                        {{ $batch->start_date->translatedFormat('d F Y') }}
                        @if($batch->end_date) &ndash; {{ $batch->end_date->translatedFormat('d F Y') }} @endif
                    @else - @endif
                </td>
            </tr>
            <tr><td class="pr-4 py-0.5 text-gray-500">Jumlah Peserta</td><td class="py-0.5 text-gray-800">: {{ $peserta->count() }} orang</td></tr>
        </table>

        <table class="w-full border-collapse text-sm mb-10">
            <thead>
                <tr class="bg-gray-100 print:bg-gray-200">
                    <th class="border border-gray-400 px-3 py-2 text-center font-semibold w-8">No</th>
                    <th class="border border-gray-400 px-3 py-2 text-left font-semibold">Nama Siswa</th>
                    <th class="border border-gray-400 px-3 py-2 text-left font-semibold">Wilayah</th>
                    <th class="border border-gray-400 px-3 py-2 text-left font-semibold">Lingkungan</th>
                    <th class="border border-gray-400 px-3 py-2 text-center font-semibold w-32">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peserta as $i => $p)
                <tr>
                    <td class="border border-gray-300 px-3 py-2 text-center text-gray-500">{{ $i + 1 }}</td>
                    <td class="border border-gray-300 px-3 py-2 font-medium text-gray-800">{{ $p->name }}</td>
                    <td class="border border-gray-300 px-3 py-2 text-gray-600">{{ $p->profile?->wilayah ?? '-' }}</td>
                    <td class="border border-gray-300 px-3 py-2 text-gray-600">{{ $p->profile?->lingkungan ?? '-' }}</td>
                    <td class="border border-gray-300 px-3 py-2 text-center font-medium">
                        {{ $p->pivot->lulus === true ? 'Lulus' : ($p->pivot->lulus === false ? 'Tidak Lulus' : '-') }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="border border-gray-300 px-3 py-6 text-center text-gray-400">Belum ada peserta terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>

        @if($batch->status === 'completed')
        @php $namaSakramen = preg_replace('/^Calon\s+/i', '', $batch->program->name); @endphp
        <div class="mb-10 text-sm text-gray-800 leading-loose border-t border-gray-200 pt-6">
            <p>Penerimaan Sakramen <strong>{{ $namaSakramen }}</strong> telah dilaksanakan pada tanggal
                <span class="inline-block border-b border-gray-800 min-w-[160px] text-center">{{ $batch->tanggal_sakramen?->translatedFormat('d F Y') ?? '' }}</span>,
                oleh <span class="inline-block border-b border-gray-800 min-w-[200px] text-center">{{ $batch->nama_romo ?? '' }}</span>,
                di Gereja <strong>Paroki Maria Marganingsih Kalasan</strong>.
            </p>
        </div>
        <div class="text-right text-sm text-gray-800 mb-6">Kalasan, {{ now()->translatedFormat('d F Y') }}</div>
        <div class="flex justify-between gap-12 text-sm text-gray-800">
            <div class="text-center w-56">
                <p class="mb-1">Katekis,</p>
                <div class="mt-20 border-b border-gray-800 pb-1 mx-auto w-full">
                    <p class="font-semibold">{{ $batch->katekis->first()?->name ?? '' }}</p>
                </div>
            </div>
            <div class="text-center w-56">
                <p class="mb-1">Romo Paroki,</p>
                <div class="mt-20 border-b border-gray-800 pb-1 mx-auto w-full">
                    <p class="font-semibold">{{ $batch->nama_romo ?? '' }}</p>
                </div>
            </div>
        </div>
        @else
        <div class="print:hidden border-t border-gray-100 pt-6 text-xs text-gray-400 text-center">
            Pernyataan dan tanda tangan muncul setelah status kelas diubah menjadi <strong>Selesai</strong>.
        </div>
        @endif
    </div>
</div>

<style>
@media print {
    @page { size: A4; margin: 1.5cm 2cm; }
    body { background: white; }
}
</style>

@push('scripts')
<script>
const params   = new URLSearchParams(window.location.search);
const activeTab = params.get('tab') || 'peserta';

function switchTab(name) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('border-blue-600', 'text-blue-600');
        b.classList.add('border-transparent', 'text-gray-500');
    });

    const panel = document.getElementById('tab-' + name);
    if (panel) panel.classList.remove('hidden');

    const btn = document.querySelector(`[data-tab="${name}"]`);
    if (btn) {
        btn.classList.remove('border-transparent', 'text-gray-500');
        btn.classList.add('border-blue-600', 'text-blue-600');
    }

    const url = new URL(window.location);
    url.searchParams.set('tab', name);
    history.replaceState(null, '', url);
}

document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => switchTab(btn.dataset.tab));
});

switchTab(activeTab);
</script>
@endpush
@endsection
