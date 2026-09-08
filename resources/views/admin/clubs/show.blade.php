@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <p class="text-sm font-medium text-indigo-600">Master Data</p>
                <h1 class="text-3xl font-bold text-slate-900">Detail Klub</h1>
            </div>
            <a href="{{ route('admin.clubs.index') }}"
                class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700">Kembali</a>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4 text-slate-700">
            <div>
                <div class="text-xs uppercase tracking-wide text-slate-500">Nama</div>
                <div class="mt-1 text-lg font-semibold text-slate-900">{{ $club->name }}</div>
            </div>

            <div>
                <div class="text-xs uppercase tracking-wide text-slate-500">Deskripsi</div>
                <div class="mt-1">{{ $club->description ?? '-' }}</div>
            </div>

            <div>
                <div class="text-xs uppercase tracking-wide text-slate-500">Status</div>
                <div class="mt-1">
                    <span
                        class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $club->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                        {{ $club->status }}
                    </span>
                </div>
            </div>
        </div>
    </div>
@endsection
