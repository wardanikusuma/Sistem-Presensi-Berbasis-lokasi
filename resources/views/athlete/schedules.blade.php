@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <p class="text-sm font-medium text-indigo-600">Jadwal</p>
            <h1 class="text-3xl font-bold text-slate-900">Jadwal Latihan Mingguan</h1>
            <p class="mt-1 text-slate-500">Jadwal latihan keseluruhan dari Senin sampai Minggu berdasarkan klub yang Anda ikuti.</p>
        </div>

        <div class="space-y-4">
            @foreach ($daysOfWeek as $day)
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="flex items-center gap-3 px-5 py-3 border-b border-slate-100 {{ $day === now()->translatedFormat('l') ? 'bg-indigo-50' : 'bg-slate-50' }}">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ $day === now()->translatedFormat('l') ? 'bg-indigo-600 text-white' : 'bg-slate-200 text-slate-600' }} text-xs font-bold">
                            {{ mb_substr($day, 0, 2) }}
                        </div>
                        <h2 class="text-base font-semibold {{ $day === now()->translatedFormat('l') ? 'text-indigo-700' : 'text-slate-800' }}">
                            {{ $day }}
                            @if ($day === now()->translatedFormat('l'))
                                <span class="ml-2 inline-flex rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-700">Hari ini</span>
                            @endif
                        </h2>
                    </div>

                    <div class="p-4">
                        @if (isset($schedules[$day]) && $schedules[$day]->isNotEmpty())
                            <div class="space-y-3">
                                @foreach ($schedules[$day] as $schedule)
                                    <div class="flex items-center justify-between rounded-lg border border-slate-100 bg-slate-50 p-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <p class="font-semibold text-slate-900">{{ $schedule->club->name ?? '-' }}</p>
                                                <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $schedule->activity_type === 'sekolah' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                                    {{ $schedule->activity_type === 'sekolah' ? 'Sekolah' : 'Luar Sekolah' }}
                                                </span>
                                            </div>
                                            <p class="mt-1 text-sm text-slate-500">
                                                📍 {{ $schedule->trainingLocation->name ?? 'Lokasi belum ditentukan' }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-semibold text-slate-800">
                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} — {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-slate-400 italic">Tidak ada jadwal latihan.</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
