@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6 flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-indigo-600">Dashboard</p>
                <h1 class="mt-1 text-3xl font-bold text-slate-900">Monitoring Sekolah</h1>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.athletes.create') }}"
                    class="rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">Tambah
                    Atlet</a>
                <a href="{{ route('admin.clubs.create') }}"
                    class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Tambah
                    Klub</a>
                <a href="{{ route('admin.training-locations.create') }}"
                    class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Tambah
                    Lokasi</a>
                <a href="{{ route('admin.training-schedules.create') }}"
                    class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Tambah
                    Jadwal</a>
                <a href="{{ route('admin.training-sessions.create') }}"
                    class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Buat
                    Sesi</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-4">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Total Atlet Aktif</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['totalActiveAthletes'] }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Total Klub Aktif</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['totalActiveClubs'] }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Total Lokasi Aktif</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['totalActiveLocations'] }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Total Jadwal Aktif</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['totalActiveSchedules'] }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Total Sesi Latihan</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['totalTrainingSessions'] }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Total Kehadiran</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['totalAttendances'] }}</p>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Perlu Verifikasi</h2>
                <p class="mt-2 text-3xl font-bold text-amber-600">{{ $stats['totalNeedVerification'] }}</p>
                <p class="mt-2 text-sm text-slate-500">Data presensi yang status lokasi masih perlu validasi.</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Kehadiran Hari Ini</h2>
                <p class="mt-2 text-3xl font-bold text-emerald-600">{{ $attendanceSummary['hadir'] }}</p>
                <p class="mt-2 text-sm text-slate-500">Total status hadir dari seluruh data presensi.</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Lokasi Tidak Sesuai</h2>
                <p class="mt-2 text-3xl font-bold text-red-600">{{ $locationMonitoring['perlu_verifikasi'] }}</p>
                <p class="mt-2 text-sm text-slate-500">Jumlah presensi dengan status lokasi perlu verifikasi.</p>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 xl:grid-cols-2 gap-6">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900">Ringkasan Klub</h2>
                    <a href="{{ route('admin.clubs.index') }}" class="text-sm font-medium text-indigo-600">Lihat semua</a>
                </div>

                @if ($clubs->isEmpty())
                    <div class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-4 text-sm text-slate-500">
                        Belum ada klub aktif.</div>
                @else
                    <div class="overflow-hidden rounded-lg border border-slate-200">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-3 py-2 text-left font-semibold text-slate-600">Klub</th>
                                    <th class="px-3 py-2 text-left font-semibold text-slate-600">Atlet</th>
                                    <th class="px-3 py-2 text-left font-semibold text-slate-600">Jadwal</th>
                                    <th class="px-3 py-2 text-left font-semibold text-slate-600">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @foreach ($clubs as $club)
                                    <tr>
                                        <td class="px-3 py-3">
                                            <a href="{{ route('admin.clubs.show', $club) }}"
                                                class="font-medium text-indigo-600 hover:text-indigo-500">{{ $club->name }}</a>
                                        </td>
                                        <td class="px-3 py-3 text-slate-700">{{ $club->active_athletes_count ?? 0 }}</td>
                                        <td class="px-3 py-3 text-slate-700">{{ $club->active_schedules_count ?? 0 }}</td>
                                        <td class="px-3 py-3">
                                            <span
                                                class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $club->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                                {{ $club->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900">Ringkasan Presensi</h2>
                </div>

                @if ($stats['totalAttendances'] === 0)
                    <div class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-4 text-sm text-slate-500">
                        Belum ada data attendance. Dashboard akan menampilkan angka 0 hingga data mulai masuk.</div>
                @else
                    <div class="space-y-3">
                        <div class="flex items-center justify-between rounded-lg bg-emerald-50 p-3 text-emerald-700">
                            <span>Hadir</span><span class="font-bold">{{ $attendanceSummary['hadir'] }}</span></div>
                        <div class="flex items-center justify-between rounded-lg bg-amber-50 p-3 text-amber-700">
                            <span>Terlambat</span><span class="font-bold">{{ $attendanceSummary['terlambat'] }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-lg bg-sky-50 p-3 text-sky-700">
                            <span>Izin</span><span class="font-bold">{{ $attendanceSummary['izin'] }}</span></div>
                        <div class="flex items-center justify-between rounded-lg bg-violet-50 p-3 text-violet-700">
                            <span>Sakit</span><span class="font-bold">{{ $attendanceSummary['sakit'] }}</span></div>
                        <div class="flex items-center justify-between rounded-lg bg-rose-50 p-3 text-rose-700">
                            <span>Alpa</span><span class="font-bold">{{ $attendanceSummary['alpa'] }}</span></div>
                        <div class="flex items-center justify-between rounded-lg bg-yellow-50 p-3 text-yellow-700">
                            <span>Perlu Verifikasi Lokasi</span><span
                                class="font-bold">{{ $attendanceSummary['perlu_verifikasi'] }}</span></div>
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 xl:grid-cols-2 gap-6">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900">Jadwal Latihan Terdekat</h2>
                </div>

                @if ($upcomingSessions->isEmpty())
                    <div class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-4 text-sm text-slate-500">
                        Tidak ada jadwal yang tersedia untuk saat ini.</div>
                @else
                    <div class="overflow-hidden rounded-lg border border-slate-200">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-3 py-2 text-left font-semibold text-slate-600">Klub</th>
                                    <th class="px-3 py-2 text-left font-semibold text-slate-600">Tanggal</th>
                                    <th class="px-3 py-2 text-left font-semibold text-slate-600">Jam</th>
                                    <th class="px-3 py-2 text-left font-semibold text-slate-600">Lokasi</th>
                                    <th class="px-3 py-2 text-left font-semibold text-slate-600">Jenis</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @foreach ($upcomingSessions as $session)
                                    <tr>
                                        <td class="px-3 py-3">{{ $session->trainingSchedule->club->name ?? '-' }}</td>
                                        <td class="px-3 py-3">
                                            {{ \Carbon\Carbon::parse($session->date)->translatedFormat('d M Y') }}</td>
                                        <td class="px-3 py-3">{{ $session->start_time }} - {{ $session->end_time }}</td>
                                        <td class="px-3 py-3">
                                            {{ $session->trainingSchedule->trainingLocation->name ?? '-' }}</td>
                                        <td class="px-3 py-3">
                                            <span
                                                class="inline-flex rounded-full bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700">
                                                {{ $session->trainingSchedule->activity_type === 'sekolah' ? 'Sekolah' : 'Luar Sekolah' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900">Sesi Latihan Hari Ini</h2>
                </div>

                @if ($todaySessions->isEmpty())
                    <div class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-4 text-sm text-slate-500">
                        Tidak ada sesi latihan untuk hari ini.</div>
                @else
                    <div class="space-y-3">
                        @foreach ($todaySessions as $session)
                            <div class="rounded-lg border border-slate-200 p-3">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="font-semibold text-slate-900">
                                        {{ $session->trainingSchedule->club->name ?? '-' }}</div>
                                    <span
                                        class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $session->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">{{ $session->status }}</span>
                                </div>
                                <div class="mt-2 text-sm text-slate-600">
                                    <p>Lokasi: {{ $session->trainingSchedule->trainingLocation->name ?? '-' }}</p>
                                    <p>Jam: {{ $session->start_time }} - {{ $session->end_time }}</p>
                                    <p>Jenis:
                                        {{ $session->trainingSchedule->activity_type === 'sekolah' ? 'Sekolah' : 'Luar Sekolah' }}
                                    </p>
                                    <p>Presensi: {{ $session->attendances->count() }} atlet</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-8 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-900">Monitoring Lokasi</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4">
                    <div class="text-sm text-emerald-700">Presensi dengan status lokasi sesuai</div>
                    <div class="mt-2 text-3xl font-bold text-emerald-700">{{ $locationMonitoring['sesuai'] }}</div>
                </div>
                <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-4">
                    <div class="text-sm text-yellow-700">Presensi dengan status lokasi perlu verifikasi</div>
                    <div class="mt-2 text-3xl font-bold text-yellow-700">{{ $locationMonitoring['perlu_verifikasi'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
