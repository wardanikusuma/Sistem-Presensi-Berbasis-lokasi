@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Welcome Section --}}
        <div class="mb-8">
            <p class="text-sm font-medium text-indigo-600">Dashboard Atlet</p>
            <h1 class="text-3xl font-bold text-slate-900">Selamat Datang, {{ $athlete?->user?->name ?? Auth::user()->name }}!</h1>
            <p class="mt-1 text-slate-500">Berikut ringkasan aktivitas latihan Anda.</p>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-100">
                        <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wide">Klub</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $clubs->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100">
                        <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wide">Total Hadir</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ $totalHadir }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-100">
                        <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wide">Alpha</p>
                        <p class="text-2xl font-bold text-red-600">{{ $totalAlpha }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100">
                        <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wide">Izin</p>
                        <p class="text-2xl font-bold text-amber-600">{{ $totalIzin }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Today's Schedule Carousel --}}
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Jadwal Hari Ini : {{ now()->translatedFormat('l, d F Y') }}</h2>
            @if ($todaySessions->isEmpty())
                <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-5 text-sm text-slate-500">
                    Tidak ada jadwal latihan untuk hari ini.
                </div>
            @elseif ($todaySessions->count() === 1)
                @php $session = $todaySessions->first(); @endphp
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-lg font-bold text-slate-900">{{ $session->trainingSchedule->club->name ?? 'Klub' }}</p>
                            <p class="text-sm text-slate-500">{{ $session->trainingSchedule->trainingLocation->name ?? 'Lokasi' }}</p>
                        </div>
                        <a href="{{ route('athlete.attendance') }}"
                            class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500 transition">
                            Presensi Sekarang
                        </a>
                    </div>
                    <div class="mt-3 grid grid-cols-2 gap-2 text-sm text-slate-600">
                        <div><span class="font-medium">Tanggal:</span> {{ \Carbon\Carbon::parse($session->date)->translatedFormat('d M Y') }}</div>
                        <div><span class="font-medium">Jam:</span> {{ $session->start_time }} - {{ $session->end_time }}</div>
                    </div>
                </div>
            @else
                {{-- Moving/Carousel Div for Multiple Schedules --}}
                <div class="relative overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm" x-data="{ currentSlide: 0, totalSlides: {{ $todaySessions->count() }} }" x-init="setInterval(() => currentSlide = (currentSlide + 1) % totalSlides, 4000)">
                    <div class="flex transition-transform duration-500 ease-in-out" :style="'transform: translateX(-' + (currentSlide * 100) + '%)'">
                        @foreach ($todaySessions as $session)
                            <div class="w-full flex-shrink-0 p-5">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-lg font-bold text-slate-900">{{ $session->trainingSchedule->club->name ?? 'Klub' }}</p>
                                        <p class="text-sm text-slate-500">{{ $session->trainingSchedule->trainingLocation->name ?? 'Lokasi' }}</p>
                                    </div>
                                    <a href="{{ route('athlete.attendance') }}"
                                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500 transition">
                                        Presensi
                                    </a>
                                </div>
                                <div class="mt-3 grid grid-cols-2 gap-2 text-sm text-slate-600">
                                    <div><span class="font-medium">Tanggal:</span> {{ \Carbon\Carbon::parse($session->date)->translatedFormat('d M Y') }}</div>
                                    <div><span class="font-medium">Jam:</span> {{ $session->start_time }} - {{ $session->end_time }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{-- Slide indicators --}}
                    <div class="flex items-center justify-center gap-2 pb-4">
                        @foreach ($todaySessions as $idx => $s)
                            <button @click="currentSlide = {{ $loop->index }}"
                                :class="currentSlide === {{ $loop->index }} ? 'bg-indigo-600 w-6' : 'bg-slate-300 w-2'"
                                class="h-2 rounded-full transition-all duration-300"></button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Clubs --}}
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Klub yang Diikuti</h2>
            @if ($clubs->isEmpty())
                <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-5 text-sm text-slate-500">
                    Anda belum terdaftar di klub manapun.
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($clubs as $club)
                        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:border-indigo-300 transition">
                            <p class="font-bold text-slate-900">{{ $club->name }}</p>
                            <p class="mt-1 text-sm text-slate-500 line-clamp-2">{{ $club->description ?? 'Tidak ada deskripsi.' }}</p>
                            <span class="mt-2 inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $club->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ $club->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Upcoming Sessions --}}
        <div class="grid grid-cols-1 gap-6">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900">Sesi Mendatang</h2>
                </div>

                @if ($upcomingSessions->isEmpty())
                    <div class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-4 text-sm text-slate-500">
                        Belum ada sesi latihan mendatang.</div>
                @else
                    <div class="space-y-3">
                        @foreach ($upcomingSessions as $session)
                            <div class="rounded-lg border border-slate-200 p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-slate-900">
                                            {{ $session->trainingSchedule->club->name ?? 'Klub' }}</p>
                                        <p class="text-sm text-slate-500">
                                            {{ $session->trainingSchedule->trainingLocation->name ?? 'Lokasi' }}</p>
                                    </div>
                                    <a href="{{ route('athlete.attendance') }}"
                                        class="rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500 transition">Presensi
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


        </div>
    </div>
@endsection
