@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-indigo-600">Master Data</p>
                <h1 class="text-3xl font-bold text-slate-900">Detail Jadwal Latihan</h1>
            </div>
            <a href="{{ route('admin.training-schedules.index') }}"
                class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                Kembali
            </a>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-slate-700">
                <div>
                    <dt class="text-slate-500">Klub</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $trainingSchedule->club?->name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Lokasi</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $trainingSchedule->trainingLocation?->name ?? '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-slate-500">Hari</dt>
                    <dd class="mt-1 font-semibold text-slate-900">
                        {{ ucfirst(str_replace('_', ' ', $trainingSchedule->day_of_week)) }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Jenis</dt>
                    <dd class="mt-1 font-semibold text-slate-900">
                        {{ $trainingSchedule->activity_type === 'sekolah' ? 'Sekolah' : 'Luar Sekolah' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Jam</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $trainingSchedule->start_time }} -
                        {{ $trainingSchedule->end_time }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Status</dt>
                    <dd class="mt-1">
                        <span
                            class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $trainingSchedule->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                            {{ $trainingSchedule->status }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
@endsection
