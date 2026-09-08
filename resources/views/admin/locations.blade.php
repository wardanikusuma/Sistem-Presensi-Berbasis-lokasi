@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-sm font-medium text-indigo-600">Data</p>
            <h1 class="text-3xl font-bold text-slate-900">Lokasi Latihan</h1>
        </div>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium">Tambah Lokasi</button>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 text-slate-600">
        Lokasi latihan disimpan bersama koordinat dan radius validasi.
    </div>
</div>
@endsection
