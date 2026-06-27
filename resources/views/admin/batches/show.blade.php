@extends('layouts.app')
@section('title', $batch->name)

@section('content')

{{-- Toolbar (hidden on print) --}}
<div class="print:hidden mb-4 flex items-center justify-between flex-wrap gap-3">
    <a href="{{ url()->previous() }}" class="text-sm text-gray-500 hover:text-blue-600">&larr; Kembali</a>
    <div class="flex gap-2">
        <a href="{{ route('admin.batches.edit', $batch) }}"
           class="text-sm font-medium bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-xl transition-colors shadow-sm">
            Edit Kelas
        </a>
        <button onclick="window.print()"
                class="text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl transition-colors shadow-sm">
            Cetak Dokumen
        </button>
    </div>
</div>

{{-- Panel isi data dokumen (hidden on print) --}}
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

{{-- Document --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 md:p-12 max-w-4xl mx-auto print:shadow-none print:border-none print:rounded-none print:p-0 print:max-w-none">

    {{-- Kop Gereja --}}
    <div class="flex items-center gap-5 pb-4 border-b-4 border-double border-gray-800 mb-6">
        <img src="{{ asset('img/LOGO PAROKI-WARNA.png') }}" alt="Logo Paroki" class="w-16 h-16 object-contain shrink-0">
        <div class="flex-1 text-center">
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-500">Keuskupan Agung Semarang</p>
            <h1 class="text-xl font-bold uppercase text-gray-900 leading-tight">Paroki Maria Marganingsih Kalasan</h1>
            <p class="text-xs text-gray-500 mt-0.5">Program Katekese Sakramen Inisiasi</p>
        </div>
        <div class="w-16 shrink-0"></div>
    </div>

    {{-- Judul Dokumen --}}
    <div class="text-center mb-8">
        <h2 class="text-base font-bold uppercase underline tracking-wide text-gray-900">
            Daftar Peserta Kelas {{ $batch->program->name }}
        </h2>
        @if($batch->status === 'active')
            <span class="print:hidden inline-block mt-2 text-xs font-semibold bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full">
                Kelas Sedang Berjalan
            </span>
        @endif
    </div>

    {{-- Info Kelas --}}
    <table class="text-sm mb-8 w-auto">
        <tr>
            <td class="pr-4 py-0.5 text-gray-500 w-40">Nama Kelas</td>
            <td class="py-0.5 text-gray-800">: {{ $batch->name }}</td>
        </tr>
        <tr>
            <td class="pr-4 py-0.5 text-gray-500">Program</td>
            <td class="py-0.5 text-gray-800">: Pendampingan {{ $batch->program->name }}</td>
        </tr>
        <tr>
            <td class="pr-4 py-0.5 text-gray-500">Katekis</td>
            <td class="py-0.5 text-gray-800">: {{ $batch->katekis->pluck('name')->join(', ') ?: '-' }}</td>
        </tr>
        <tr>
            <td class="pr-4 py-0.5 text-gray-500">Periode</td>
            <td class="py-0.5 text-gray-800">:
                @if($batch->start_date)
                    {{ $batch->start_date->translatedFormat('d F Y') }}
                    @if($batch->end_date) &ndash; {{ $batch->end_date->translatedFormat('d F Y') }} @endif
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td class="pr-4 py-0.5 text-gray-500">Jumlah Peserta</td>
            <td class="py-0.5 text-gray-800">: {{ $peserta->count() }} orang</td>
        </tr>
    </table>

    {{-- Tabel Peserta --}}
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
            <tr class="hover:bg-gray-50 print:hover:bg-transparent">
                <td class="border border-gray-300 px-3 py-2 text-center text-gray-500">{{ $i + 1 }}</td>
                <td class="border border-gray-300 px-3 py-2 font-medium text-gray-800">{{ $p->name }}</td>
                <td class="border border-gray-300 px-3 py-2 text-gray-600">{{ $p->profile?->wilayah ?? '-' }}</td>
                <td class="border border-gray-300 px-3 py-2 text-gray-600">{{ $p->profile?->lingkungan ?? '-' }}</td>
                <td class="border border-gray-300 px-3 py-2 text-center">
                    <form method="POST" action="{{ route('admin.batches.peserta.kelulusan', [$batch, $p]) }}"
                          class="print:hidden">
                        @csrf @method('PATCH')
                        <select name="lulus" onchange="this.form.submit()"
                                class="text-xs border border-gray-200 rounded-lg px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-300
                                    {{ $p->pivot->lulus === true ? 'bg-emerald-50 text-emerald-700' : ($p->pivot->lulus === false ? 'bg-red-50 text-red-700' : 'text-gray-400') }}">
                            <option value="" @selected($p->pivot->lulus === null)>— Belum</option>
                            <option value="1" @selected($p->pivot->lulus === true)>Lulus</option>
                            <option value="0" @selected($p->pivot->lulus === false)>Tidak Lulus</option>
                        </select>
                    </form>
                    <span class="hidden print:inline font-medium">
                        {{ $p->pivot->lulus === true ? 'Lulus' : ($p->pivot->lulus === false ? 'Tidak Lulus' : '-') }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="border border-gray-300 px-3 py-6 text-center text-gray-400">
                    Belum ada peserta terdaftar.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($batch->status === 'completed')
    {{-- Pernyataan Sakramen --}}
    @php
        $namaSakramen = preg_replace('/^Calon\s+/i', '', $batch->program->name);
    @endphp
    <div class="mb-10 text-sm text-gray-800 leading-loose border-t border-gray-200 pt-6">
        <p>
            Penerimaan Sakramen <strong>{{ $namaSakramen }}</strong> telah dilaksanakan pada tanggal
            <span class="inline-block border-b border-gray-800 min-w-[160px] text-center">
                {{ $batch->tanggal_sakramen?->translatedFormat('d F Y') ?? '' }}
            </span>,
            oleh
            <span class="inline-block border-b border-gray-800 min-w-[200px] text-center">
                {{ $batch->nama_romo ?? '' }}
            </span>,
            di Gereja <strong>Paroki Maria Marganingsih Kalasan</strong>.
        </p>
    </div>

    {{-- Area Tanda Tangan --}}
    <div class="text-right text-sm text-gray-800 mb-6">
        Kalasan, {{ now()->translatedFormat('d F Y') }}
    </div>
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
        Pernyataan dan area tanda tangan akan muncul setelah status kelas diubah menjadi <strong>Selesai</strong>.
    </div>
    @endif

</div>

<style>
@media print {
    @page { size: A4; margin: 1.5cm 2cm; }
    body { background: white; }
}
</style>
@endsection
