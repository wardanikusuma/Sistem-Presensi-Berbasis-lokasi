@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <p class="text-sm font-medium text-indigo-600">Master Data</p>
            <h1 class="text-3xl font-bold text-slate-900">Edit Sesi Latihan</h1>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <form action="{{ route('admin.training-sessions.update', $trainingSession) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Jadwal Latihan</label>
                        <select name="training_schedule_id"
                            class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                            @foreach ($trainingSchedules as $trainingSchedule)
                                <option value="{{ $trainingSchedule->id }}"
                                    {{ old('training_schedule_id', $trainingSession->training_schedule_id) == $trainingSchedule->id ? 'selected' : '' }}>
                                    {{ $trainingSchedule->club?->name ?? '-' }} • {{ $trainingSchedule->day_of_week }} •
                                    {{ $trainingSchedule->start_time }} - {{ $trainingSchedule->end_time }}
                                </option>
                            @endforeach
                        </select>
                        @error('training_schedule_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Tanggal</label>
                        <input type="date" name="date" value="{{ old('date', $trainingSession->date) }}"
                            class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Status</label>
                        <select name="status"
                            class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="scheduled"
                                {{ old('status', $trainingSession->status) === 'scheduled' ? 'selected' : '' }}>Scheduled
                            </option>
                            <option value="active"
                                {{ old('status', $trainingSession->status) === 'active' ? 'selected' : '' }}>Active
                            </option>
                            <option value="completed"
                                {{ old('status', $trainingSession->status) === 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="cancelled"
                                {{ old('status', $trainingSession->status) === 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                            <option value="inactive"
                                {{ old('status', $trainingSession->status) === 'inactive' ? 'selected' : '' }}>Inactive
                            </option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Jam Mulai</label>
                        <input type="time" name="start_time"
                            value="{{ old('start_time', $trainingSession->start_time) }}"
                            class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Jam Selesai</label>
                        <input type="time" name="end_time" value="{{ old('end_time', $trainingSession->end_time) }}"
                            class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('admin.training-sessions.index') }}"
                        class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700">Batal</a>
                    <button type="submit"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
