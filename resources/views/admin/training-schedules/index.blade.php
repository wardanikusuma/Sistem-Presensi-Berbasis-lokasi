@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <p class="text-sm font-medium text-indigo-600">Master Data</p>
                <h1 class="text-3xl font-bold text-slate-900">Jadwal Latihan</h1>
            </div>
            <a href="{{ route('admin.training-schedules.create') }}"
                class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-500">
                Tambah Jadwal
            </a>
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
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-600">Klub</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-600">Lokasi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-600">Hari</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-600">Jam</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-600">Tipe</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-600">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm text-slate-700">
                    @forelse ($trainingSchedules as $trainingSchedule)
                        <tr>
                            <td class="px-4 py-3 font-medium text-slate-900">{{ $trainingSchedule->club?->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3">{{ $trainingSchedule->trainingLocation?->name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ ucfirst(str_replace('_', ' ', $trainingSchedule->day_of_week)) }}</td>
                            <td class="px-4 py-3">{{ $trainingSchedule->start_time }} - {{ $trainingSchedule->end_time }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $trainingSchedule->activity_type === 'sekolah' ? 'Sekolah' : 'Luar Sekolah' }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $trainingSchedule->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $trainingSchedule->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.training-schedules.show', $trainingSchedule) }}"
                                        class="rounded bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700">Detail</a>
                                    <a href="{{ route('admin.training-schedules.edit', $trainingSchedule) }}"
                                        class="rounded bg-amber-100 px-2 py-1 text-xs font-medium text-amber-700">Edit</a>
                                    <form action="{{ route('admin.training-schedules.toggle-status', $trainingSchedule) }}"
                                        method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="rounded {{ $trainingSchedule->status === 'active' ? 'bg-yellow-100 text-yellow-700' : 'bg-emerald-100 text-emerald-700' }} px-2 py-1 text-xs font-medium">
                                            {{ $trainingSchedule->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.training-schedules.destroy', $trainingSchedule) }}"
                                        method="POST" class="inline" onsubmit="return confirm('Yakin hapus jadwal ini?')">
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
                            <td colspan="7" class="px-4 py-8 text-center text-slate-500">Belum ada jadwal latihan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
