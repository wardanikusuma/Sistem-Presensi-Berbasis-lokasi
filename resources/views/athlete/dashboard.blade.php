@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <p class="text-sm font-medium text-indigo-600">Dashboard Atlet</p>
            <h1 class="text-3xl font-bold text-slate-900">Halo, {{ $athlete?->user?->name ?? Auth::user()->name }}</h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <p class="text-sm text-slate-500">Klub</p>
                <p class="mt-3 text-2xl font-bold text-slate-900">{{ $clubs->count() }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <p class="text-sm text-slate-500">Sesi Tersedia</p>
                <p class="mt-3 text-2xl font-bold text-slate-900">{{ $validSessions->count() }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <p class="text-sm text-slate-500">Riwayat Terbaru</p>
                <p class="mt-3 text-2xl font-bold text-emerald-600">{{ $recentAttendances->count() }}</p>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 xl:grid-cols-2 gap-6">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900">Sesi latihan yang dapat digunakan untuk presensi</h2>
                </div>

                @if ($validSessions->isEmpty())
                    <div class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-4 text-sm text-slate-500">
                        Belum ada sesi latihan yang aktif untuk klub Anda saat ini.</div>
                @else
                    <div class="space-y-3">
                        @foreach ($validSessions as $session)
                            <div class="rounded-lg border border-slate-200 p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-slate-900">
                                            {{ $session->trainingSchedule->club->name ?? 'Klub' }}</p>
                                        <p class="text-sm text-slate-500">
                                            {{ $session->trainingSchedule->trainingLocation->name ?? 'Lokasi' }}</p>
                                    </div>
                                    <a href="{{ route('athlete.attendance') }}"
                                        class="rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white">Presensi
                                        Sekarang</a>
                                </div>
                                <div class="mt-3 grid grid-cols-2 gap-2 text-sm text-slate-600">
                                    <div><span class="font-medium">Tanggal:</span>
                                        {{ \Carbon\Carbon::parse($session->date)->translatedFormat('d M Y') }}</div>
                                    <div><span class="font-medium">Jam:</span> {{ $session->start_time }} -
                                        {{ $session->end_time }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900">Riwayat presensi terbaru</h2>
                    <a href="{{ route('athlete.attendances') }}" class="text-sm font-medium text-indigo-600">Lihat
                        semua</a>
                </div>

                @if ($recentAttendances->isEmpty())
                    <div class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-4 text-sm text-slate-500">
                        Belum ada riwayat presensi.</div>
                @else
                    <div class="space-y-3">
                        @foreach ($recentAttendances as $attendance)
                            <div class="rounded-lg border border-slate-200 p-3">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="font-semibold text-slate-900">
                                        {{ $attendance->trainingSession->trainingSchedule->club->name ?? '-' }}</p>
                                    <span
                                        class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $attendance->location_status === 'sesuai' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $attendance->location_status }}
                                    </span>
                                </div>
                                <p class="mt-1 text-sm text-slate-600">
                                    {{ \Carbon\Carbon::parse($attendance->check_in_at)->translatedFormat('d M Y, H:i') }}
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    Lokasi: {{ $attendance->trainingLocation?->name ?? '-' }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
