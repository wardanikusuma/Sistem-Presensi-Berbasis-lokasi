@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-medium text-indigo-600">Rekap</p>
                <h1 class="text-3xl font-bold text-slate-900">Rekap Kehadiran</h1>
            </div>
            <a href="{{ route('admin.attendance-reports.export') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-500">
                Export CSV
            </a>
        </div>

        <div class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach ([['label' => 'Hadir', 'value' => $summary['hadir'] ?? 0, 'color' => 'emerald'], ['label' => 'Terlambat', 'value' => $summary['terlambat'] ?? 0, 'color' => 'amber'], ['label' => 'Izin', 'value' => $summary['izin'] ?? 0, 'color' => 'blue'], ['label' => 'Sakit', 'value' => $summary['sakit'] ?? 0, 'color' => 'purple']] as $item)
                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="text-sm text-slate-500">{{ $item['label'] }}</div>
                    <div class="mt-2 text-3xl font-bold text-{{ $item['color'] }}-700">{{ $item['value'] }}</div>
                </div>
            @endforeach
        </div>

        <div class="mb-6 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.attendance-reports') }}" class="grid gap-4 md:grid-cols-5">
                <div>
                    <label for="date_from" class="mb-1 block text-sm font-medium text-slate-700">Dari</label>
                    <input id="date_from" type="date" name="date_from" value="{{ request('date_from') }}"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>
                <div>
                    <label for="date_to" class="mb-1 block text-sm font-medium text-slate-700">Sampai</label>
                    <input id="date_to" type="date" name="date_to" value="{{ request('date_to') }}"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>
                <div>
                    <label for="club_id" class="mb-1 block text-sm font-medium text-slate-700">Klub</label>
                    <select id="club_id" name="club_id"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <option value="">Semua</option>
                        @foreach ($clubs as $club)
                            <option value="{{ $club->id }}" {{ request('club_id') == $club->id ? 'selected' : '' }}>
                                {{ $club->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="location_status" class="mb-1 block text-sm font-medium text-slate-700">Status Lokasi</label>
                    <select id="location_status" name="location_status"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <option value="">Semua</option>
                        <option value="sesuai" {{ request('location_status') === 'sesuai' ? 'selected' : '' }}>Sesuai
                        </option>
                        <option value="perlu_verifikasi"
                            {{ request('location_status') === 'perlu_verifikasi' ? 'selected' : '' }}>Perlu Verifikasi
                        </option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">Filter</button>
                </div>
            </form>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="text-sm text-slate-500">Perlu Verifikasi</div>
                <div class="mt-2 text-3xl font-bold text-amber-700">{{ $summary['perlu_verifikasi'] ?? 0 }}</div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="text-sm text-slate-500">Sesuai Lokasi</div>
                <div class="mt-2 text-3xl font-bold text-emerald-700">{{ $summary['sesuai'] ?? 0 }}</div>
            </div>
        </div>
    </div>
@endsection
