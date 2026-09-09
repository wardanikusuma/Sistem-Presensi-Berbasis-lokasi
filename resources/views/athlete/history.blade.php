@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <p class="text-sm font-medium text-indigo-600">Riwayat</p>
            <h1 class="text-3xl font-bold text-slate-900">Riwayat Presensi</h1>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Tanggal</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Klub</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Sesi</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Waktu</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Lokasi</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Jarak</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Accuracy</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Status Lokasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($attendances as $attendance)
                        <tr>
                            <td class="px-4 py-3 text-slate-700">
                                {{ \Carbon\Carbon::parse($attendance->check_in_at)->translatedFormat('d M Y') }}</td>
                            <td class="px-4 py-3 text-slate-700">
                                {{ $attendance->trainingSession->trainingSchedule->club->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-700">
                                {{ $attendance->trainingSession->trainingSchedule->activity_type ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-700">
                                {{ \Carbon\Carbon::parse($attendance->check_in_at)->translatedFormat('H:i') }}</td>
                            <td class="px-4 py-3 text-slate-700">
                                @if($attendance->location_status === 'sesuai')
                                    {{ $attendance->trainingLocation?->name ?? '-' }}
                                @else
                                    {{ $attendance->latitude }}, {{ $attendance->longitude }}
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-700">
                                {{ number_format((float) $attendance->distance_from_location, 2) }} m</td>
                            <td class="px-4 py-3 text-slate-700">
                                {{ $attendance->gps_accuracy ? number_format((float) $attendance->gps_accuracy, 2) . ' m' : '-' }}
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $attendance->location_status === 'sesuai' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $attendance->location_status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-slate-500">Belum ada riwayat presensi.</td>
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
