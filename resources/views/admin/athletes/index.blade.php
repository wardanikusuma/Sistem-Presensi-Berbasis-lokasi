@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <p class="text-sm font-medium text-indigo-600">Master Data</p>
                <h1 class="text-3xl font-bold text-slate-900">Atlet</h1>
            </div>
            <a href="{{ route('admin.athletes.create') }}"
                class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-500">Tambah
                Atlet</a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-600">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-600">NIS</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-600">Kelas</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-600">Klub</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-600">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm text-slate-700">
                    @forelse ($athletes as $athlete)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-slate-900">{{ $athlete->user?->name ?? '-' }}</div>
                                <div class="text-xs text-slate-500">{{ $athlete->user?->email ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3">{{ $athlete->nis }}</td>
                            <td class="px-4 py-3">{{ $athlete->class }}</td>
                            <td class="px-4 py-3">{{ $athlete->clubs->pluck('name')->join(', ') ?: '-' }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $athlete->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $athlete->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.athletes.show', $athlete) }}"
                                        class="rounded bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700">Detail</a>
                                    <a href="{{ route('admin.athletes.edit', $athlete) }}"
                                        class="rounded bg-amber-100 px-2 py-1 text-xs font-medium text-amber-700">Edit</a>
                                    <form action="{{ route('admin.athletes.toggle-status', $athlete) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="rounded {{ $athlete->status === 'active' ? 'bg-yellow-100 text-yellow-700' : 'bg-emerald-100 text-emerald-700' }} px-2 py-1 text-xs font-medium">
                                            {{ $athlete->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.athletes.destroy', $athlete) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Yakin hapus data atlet ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="rounded bg-red-100 px-2 py-1 text-xs font-medium text-red-700">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-500">Belum ada data atlet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
