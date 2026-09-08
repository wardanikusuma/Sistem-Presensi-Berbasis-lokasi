@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <p class="text-sm font-medium text-indigo-600">Master Data</p>
                <h1 class="text-3xl font-bold text-slate-900">Lokasi Latihan</h1>
            </div>
            <a href="{{ route('admin.training-locations.create') }}"
                class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-500">Tambah
                Lokasi</a>
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
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-600">Tipe</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-600">Radius</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-600">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm text-slate-700">
                    @forelse ($trainingLocations as $location)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-slate-900">{{ $location->name }}</div>
                                <div class="text-xs text-slate-500">{{ $location->address }}</div>
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ $location->type }}</td>
                            <td class="px-4 py-3">{{ $location->radius }} m</td>
                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $location->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $location->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.training-locations.show', $location) }}"
                                        class="rounded bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700">Detail</a>
                                    <a href="{{ route('admin.training-locations.edit', $location) }}"
                                        class="rounded bg-amber-100 px-2 py-1 text-xs font-medium text-amber-700">Edit</a>
                                    <form action="{{ route('admin.training-locations.toggle-status', $location) }}"
                                        method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="rounded {{ $location->status === 'active' ? 'bg-yellow-100 text-yellow-700' : 'bg-emerald-100 text-emerald-700' }} px-2 py-1 text-xs font-medium">
                                            {{ $location->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.training-locations.destroy', $location) }}"
                                        method="POST" class="inline" onsubmit="return confirm('Yakin hapus lokasi ini?')">
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
                            <td colspan="5" class="px-4 py-8 text-center text-slate-500">Belum ada data lokasi latihan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
