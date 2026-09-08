@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-sm font-medium text-indigo-600">Data</p>
            <h1 class="text-3xl font-bold text-slate-900">Atlet</h1>
        </div>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium">Tambah Atlet</button>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">NIS</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Kelas</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Club</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-sm text-slate-700">
                <tr>
                    <td class="px-4 py-3">Belum ada data</td>
                    <td class="px-4 py-3">-</td>
                    <td class="px-4 py-3">-</td>
                    <td class="px-4 py-3">-</td>
                    <td class="px-4 py-3"><span class="inline-flex px-2 py-1 rounded-full bg-slate-100 text-slate-600">-</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
