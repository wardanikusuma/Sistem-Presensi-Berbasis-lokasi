@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <p class="text-sm font-medium text-indigo-600">Master Data</p>
            <h1 class="text-3xl font-bold text-slate-900">Edit Lokasi Latihan</h1>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <form action="{{ route('admin.training-locations.update', $trainingLocation) }}" method="POST" class="space-y-5">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $trainingLocation->name) }}"
                            class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Alamat</label>
                        <textarea name="address" rows="3"
                            class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('address', $trainingLocation->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Latitude</label>
                        <input type="number" step="any" name="latitude"
                            value="{{ old('latitude', $trainingLocation->latitude) }}"
                            class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                        @error('latitude')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Longitude</label>
                        <input type="number" step="any" name="longitude"
                            value="{{ old('longitude', $trainingLocation->longitude) }}"
                            class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                        @error('longitude')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Radius (meter)</label>
                        <input type="number" step="any" min="1" name="radius"
                            value="{{ old('radius', $trainingLocation->radius) }}"
                            class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                        @error('radius')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Tipe</label>
                        <select name="type"
                            class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                            <option value="sekolah"
                                {{ old('type', $trainingLocation->type) === 'sekolah' ? 'selected' : '' }}>sekolah</option>
                            <option value="luar_sekolah"
                                {{ old('type', $trainingLocation->type) === 'luar_sekolah' ? 'selected' : '' }}>
                                luar_sekolah</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Status</label>
                        <select name="status"
                            class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="active"
                                {{ old('status', $trainingLocation->status) === 'active' ? 'selected' : '' }}>Active
                            </option>
                            <option value="inactive"
                                {{ old('status', $trainingLocation->status) === 'inactive' ? 'selected' : '' }}>Inactive
                            </option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('admin.training-locations.index') }}"
                        class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700">Batal</a>
                    <button type="submit"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection
