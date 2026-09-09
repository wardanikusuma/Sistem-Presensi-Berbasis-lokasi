@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <p class="text-sm font-medium text-indigo-600">Identitas Klub</p>
            <h1 class="text-3xl font-bold text-slate-900">Klub yang Diikuti</h1>
            <p class="mt-1 text-slate-500">Informasi lengkap mengenai klub yang Anda ikuti.</p>
        </div>

        @if ($clubs->isEmpty())
            <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-slate-500">
                Anda belum terdaftar di klub manapun. Silakan hubungi admin untuk mendaftarkan diri.
            </div>
        @else
            <div class="space-y-6">
                @foreach ($clubs as $club)
                    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="border-b border-slate-100 bg-slate-50 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100">
                                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <div>
                                        <h2 class="text-xl font-bold text-slate-900">{{ $club->name }}</h2>
                                        <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $club->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                            {{ $club->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-slate-500">Anggota</p>
                                    <p class="text-lg font-bold text-slate-900">{{ $club->athletes_count }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-sm font-semibold text-slate-600 uppercase tracking-wide mb-2">Tentang Klub</h3>
                            <p class="text-slate-700 leading-relaxed">
                                {{ $club->description ?? 'Belum ada deskripsi untuk klub ini.' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
