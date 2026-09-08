@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-medium text-indigo-600">Monitoring</p>
                <h1 class="text-3xl font-bold text-slate-900">Data Presensi GPS</h1>
            </div>
            <a href="{{ route('admin.attendance-reports') }}"
                class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">
                Lihat Rekap
            </a>
        </div>

        <div class="mb-6 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.attendances') }}" class="grid gap-4 md:grid-cols-5">
                <div class="md:col-span-2">
                    <label for="search" class="mb-1 block text-sm font-medium text-slate-700">Cari atlet</label>
                    <input id="search" type="text" name="search" value="{{ request('search') }}"
                        placeholder="Nama atau email"
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

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Atlet</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Klub</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Tanggal</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Waktu</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Lokasi</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Jarak</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($attendances as $attendance)
                        <tr>
                            <td class="px-4 py-3 text-slate-700">{{ $attendance->athlete?->user?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-700">
                                {{ $attendance->trainingSession?->trainingSchedule?->club?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-700">
                                {{ $attendance->check_in_at ? $attendance->check_in_at->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="px-4 py-3 text-slate-700">
                                {{ $attendance->check_in_at ? $attendance->check_in_at->translatedFormat('H:i') : '-' }}
                            </td>
                            <td class="px-4 py-3 text-slate-700">
                                {{ $attendance->trainingLocation?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-700">
                                {{ $attendance->distance_from_location !== null ? number_format((float) $attendance->distance_from_location, 2) . ' m' : '-' }}
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $attendance->location_status === 'sesuai' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $attendance->location_status }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.attendances.show', $attendance) }}"
                                    class="rounded bg-indigo-100 px-2 py-1 text-xs font-medium text-indigo-700">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-slate-500">Belum ada data presensi GPS.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($attendances->hasPages())
            <div class="mt-6">
                {{ $attendances->links() }}
            </div>
        @endif
    </div>
@endsection
