@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6 flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-indigo-600">Monitoring</p>
                <h1 class="text-3xl font-bold text-slate-900">Detail Presensi Atlet</h1>
            </div>
            <a href="{{ route('admin.attendances') }}"
                class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                Kembali
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-slate-900">Informasi Atlet</h2>
                <dl class="space-y-3 text-sm text-slate-700">
                    <div class="flex justify-between gap-4">
                        <dt>Nama</dt>
                        <dd class="font-medium text-slate-900">{{ $attendance->athlete?->user?->name ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt>Klub</dt>
                        <dd class="font-medium text-slate-900">
                            {{ $attendance->trainingSession?->trainingSchedule?->club?->name ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt>Sesi</dt>
                        <dd class="font-medium text-slate-900">
                            {{ $attendance->trainingSession?->trainingSchedule?->name ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt>Tanggal</dt>
                        <dd class="font-medium text-slate-900">
                            {{ $attendance->check_in_at ? $attendance->check_in_at->translatedFormat('d M Y') : '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt>Waktu</dt>
                        <dd class="font-medium text-slate-900">
                            {{ $attendance->check_in_at ? $attendance->check_in_at->translatedFormat('H:i') : '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt>Lokasi latihan</dt>
                        <dd class="font-medium text-slate-900">{{ $attendance->trainingLocation?->name ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-slate-900">GPS & Status</h2>
                <dl class="space-y-3 text-sm text-slate-700">
                    <div class="flex justify-between gap-4">
                        <dt>Latitude</dt>
                        <dd class="font-medium text-slate-900">{{ $attendance->latitude ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt>Longitude</dt>
                        <dd class="font-medium text-slate-900">{{ $attendance->longitude ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt>Accuracy</dt>
                        <dd class="font-medium text-slate-900">
                            {{ $attendance->gps_accuracy ? number_format((float) $attendance->gps_accuracy, 2) . ' m' : '-' }}
                        </dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt>Jarak</dt>
                        <dd class="font-medium text-slate-900">
                            {{ $attendance->distance_from_location !== null ? number_format((float) $attendance->distance_from_location, 2) . ' m' : '-' }}
                        </dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt>Status Lokasi</dt>
                        <dd><span
                                class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $attendance->location_status === 'sesuai' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ $attendance->location_status }}</span>
                        </dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt>Status Kehadiran</dt>
                        <dd class="font-medium text-slate-900">{{ $attendance->attendance_status }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="mt-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-slate-900">Verifikasi Manual</h2>

            <form action="{{ route('admin.attendances.verify', $attendance) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="verification_status" class="mb-1 block text-sm font-medium text-slate-700">Keputusan</label>
                    <select id="verification_status" name="verification_status"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <option value="verified"
                            {{ old('verification_status', $attendance->verification_status) === 'verified' ? 'selected' : '' }}>
                            Diterima</option>
                        <option value="rejected"
                            {{ old('verification_status', $attendance->verification_status) === 'rejected' ? 'selected' : '' }}>
                            Ditolak</option>
                        <option value="needs_review"
                            {{ old('verification_status', $attendance->verification_status) === 'needs_review' ? 'selected' : '' }}>
                            Perlu Review</option>
                    </select>
                </div>

                <div>
                    <label for="verification_note" class="mb-1 block text-sm font-medium text-slate-700">Catatan
                        verifikasi</label>
                    <textarea id="verification_note" name="verification_note" rows="4"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                        placeholder="Masukkan catatan verifikasi...">{{ old('verification_note', $attendance->verification_note) }}</textarea>
                </div>

                <div class="flex items-center justify-between gap-3">
                    <div class="text-sm text-slate-500">
                        @if ($attendance->verified_by)
                            Diverifikasi oleh {{ $attendance->verifier?->name ?? '-' }} pada
                            {{ $attendance->verified_at ? $attendance->verified_at->translatedFormat('d M Y H:i') : '-' }}
                        @else
                            Belum diverifikasi
                        @endif
                    </div>
                    <button type="submit"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                        Simpan Verifikasi
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
