@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <p class="text-sm font-medium text-indigo-600">Profil</p>
        <h1 class="text-3xl font-bold text-slate-900">Profil Atlet</h1>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 text-slate-600">
        <p>Nama: {{ Auth::user()->name }}</p>
        <p>Email: {{ Auth::user()->email }}</p>
    </div>
</div>
@endsection
