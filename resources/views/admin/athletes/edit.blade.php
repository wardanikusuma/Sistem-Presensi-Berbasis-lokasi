@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <p class="text-sm font-medium text-indigo-600">Master Data</p>
            <h1 class="text-3xl font-bold text-slate-900">Edit Atlet</h1>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <form action="{{ route('admin.athletes.update', $athlete) }}" method="POST" class="space-y-5">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $athlete->user?->name) }}"
                            class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Email</label>
                        <input type="email" name="email" value="{{ old('email', $athlete->user?->email) }}"
                            class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Password Baru</label>
                        <input type="password" name="password"
                            class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Kosongkan jika tidak diubah">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Status</label>
                        <select name="status"
                            class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="active" {{ old('status', $athlete->status) === 'active' ? 'selected' : '' }}>
                                Active</option>
                            <option value="inactive" {{ old('status', $athlete->status) === 'inactive' ? 'selected' : '' }}>
                                Inactive</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">NIS</label>
                        <input type="text" name="nis" value="{{ old('nis', $athlete->nis) }}"
                            class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                        @error('nis')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">NISN</label>
                        <input type="text" name="nisn" value="{{ old('nisn', $athlete->nisn) }}"
                            class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('nisn')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Kelas</label>
                        <input type="text" name="class" value="{{ old('class', $athlete->class) }}"
                            class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                        @error('class')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', $athlete->phone) }}"
                            class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Klub yang diikuti</label>
                    <div class="mt-2 space-y-2">
                        @foreach ($clubs as $club)
                            <label class="flex items-center gap-2 text-sm text-slate-700">
                                <input type="checkbox" name="club_ids[]" value="{{ $club->id }}"
                                    {{ $athlete->clubs->contains('id', $club->id) ? 'checked' : '' }}
                                    class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                {{ $club->name }}
                            </label>
                        @endforeach
                    </div>
                    @error('club_ids')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('admin.athletes.index') }}"
                        class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700">Batal</a>
                    <button type="submit"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection
