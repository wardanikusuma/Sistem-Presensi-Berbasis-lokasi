@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <p class="text-sm font-medium text-indigo-600">Profil</p>
            <h1 class="text-3xl font-bold text-slate-900">Profil Atlet</h1>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-8">
                <div class="flex items-center gap-4">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-white/20 text-2xl font-bold text-white">
                        {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">{{ $user->name }}</h2>
                        <p class="text-indigo-100">{{ $user->email }}</p>
                    </div>
                </div>
            </div>

            {{-- Info --}}
            <div class="p-6 space-y-5">
                <div>
                    <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-3">Informasi Akun</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rounded-lg bg-slate-50 p-4">
                            <p class="text-xs text-slate-500">Nama Lengkap</p>
                            <p class="mt-1 font-semibold text-slate-900">{{ $user->name }}</p>
                        </div>
                        <div class="rounded-lg bg-slate-50 p-4">
                            <p class="text-xs text-slate-500">Email</p>
                            <p class="mt-1 font-semibold text-slate-900">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>

                @if ($athlete)
                    <div>
                        <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-3">Data Atlet</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="rounded-lg bg-slate-50 p-4">
                                <p class="text-xs text-slate-500">NIS</p>
                                <p class="mt-1 font-semibold text-slate-900">{{ $athlete->nis ?? '-' }}</p>
                            </div>
                            <div class="rounded-lg bg-slate-50 p-4">
                                <p class="text-xs text-slate-500">NISN</p>
                                <p class="mt-1 font-semibold text-slate-900">{{ $athlete->nisn ?? '-' }}</p>
                            </div>
                            <div class="rounded-lg bg-slate-50 p-4">
                                <p class="text-xs text-slate-500">Kelas</p>
                                <p class="mt-1 font-semibold text-slate-900">{{ $athlete->class ?? '-' }}</p>
                            </div>
                            <div class="rounded-lg bg-slate-50 p-4">
                                <p class="text-xs text-slate-500">No. Telepon</p>
                                <p class="mt-1 font-semibold text-slate-900">{{ $athlete->phone ?? '-' }}</p>
                            </div>
                            <div class="rounded-lg bg-slate-50 p-4 md:col-span-2">
                                <p class="text-xs text-slate-500">Status</p>
                                <span class="mt-1 inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $athlete->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $athlete->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-3">Klub yang Diikuti</h3>
                        @if ($athlete->clubs->isEmpty())
                            <p class="text-sm text-slate-400 italic">Belum terdaftar di klub manapun.</p>
                        @else
                            <div class="flex flex-wrap gap-2">
                                @foreach ($athlete->clubs as $club)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 border border-indigo-200 px-3 py-1 text-sm font-medium text-indigo-700">
                                        {{ $club->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <div class="rounded-lg border border-dashed border-amber-300 bg-amber-50 p-4 text-sm text-amber-700">
                        Data atlet Anda belum terdaftar. Silakan hubungi admin.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
