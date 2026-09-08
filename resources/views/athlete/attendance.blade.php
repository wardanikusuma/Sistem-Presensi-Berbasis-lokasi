@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <p class="text-sm font-medium text-indigo-600">Presensi Atlet</p>
            <h1 class="text-3xl font-bold text-slate-900">Sesi latihan yang dapat dihadiri</h1>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($sessions->isEmpty())
            <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-5 text-slate-600">
                Saat ini tidak ada sesi latihan yang dapat Anda presensi.
            </div>
        @else
            @foreach ($sessions as $session)
                <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-500">Klub</p>
                            <p class="mt-1 text-lg font-bold text-slate-900">
                                {{ $session->trainingSchedule->club->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-500">Jenis latihan</p>
                            <p class="mt-1 text-lg font-bold text-slate-900">
                                {{ $session->trainingSchedule->activity_type === 'sekolah' ? 'Sekolah' : 'Luar Sekolah' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-500">Tanggal</p>
                            <p class="mt-1 text-base font-semibold text-slate-800">
                                {{ \Carbon\Carbon::parse($session->date)->translatedFormat('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-500">Jam</p>
                            <p class="mt-1 text-base font-semibold text-slate-800">{{ $session->start_time }} -
                                {{ $session->end_time }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-xs uppercase tracking-wide text-slate-500">Lokasi latihan</p>
                            <select id="training-location-{{ $session->id }}" data-location-select
                                class="mt-2 w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach ($trainingLocations as $location)
                                    <option value="{{ $location->id }}"
                                        {{ $location->id === $session->trainingSchedule->trainingLocation->id ? 'selected' : '' }}>
                                        {{ $location->name }} · {{ $location->address ?? 'Alamat belum diisi' }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-slate-500">Pilih lokasi tempat Anda melakukan latihan. GPS akan
                                diverifikasi terhadap lokasi ini.</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="button" data-session-id="{{ $session->id }}"
                            class="check-in-button w-full rounded-xl bg-indigo-600 px-4 py-3 text-base font-semibold text-white shadow-sm hover:bg-indigo-500">
                            Presensi Sekarang
                        </button>
                        <div
                            class="status-message mt-4 hidden rounded-lg border border-slate-200 bg-slate-50 p-3 text-sm text-slate-700">
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.check-in-button');

            buttons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const sessionId = this.getAttribute('data-session-id');
                    const message = this.parentElement.querySelector('.status-message');
                    const locationSelect = document.getElementById('training-location-' +
                    sessionId);

                    if (!navigator.geolocation) {
                        message.classList.remove('hidden');
                        message.textContent =
                            'Browser tidak mendukung GPS. Gunakan browser yang support Geolocation API.';
                        return;
                    }

                    message.classList.remove('hidden');
                    message.textContent = 'Sedang mengambil lokasi...';

                    navigator.geolocation.getCurrentPosition(function(position) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route('athlete.attendance.store') }}';

                        const csrf = document.createElement('input');
                        csrf.type = 'hidden';
                        csrf.name = '_token';
                        csrf.value = '{{ csrf_token() }}';
                        form.appendChild(csrf);

                        const sessionInput = document.createElement('input');
                        sessionInput.type = 'hidden';
                        sessionInput.name = 'training_session_id';
                        sessionInput.value = sessionId;
                        form.appendChild(sessionInput);

                        const locationInput = document.createElement('input');
                        locationInput.type = 'hidden';
                        locationInput.name = 'training_location_id';
                        locationInput.value = locationSelect.value;
                        form.appendChild(locationInput);

                        const lat = document.createElement('input');
                        lat.type = 'hidden';
                        lat.name = 'latitude';
                        lat.value = position.coords.latitude;
                        form.appendChild(lat);

                        const lng = document.createElement('input');
                        lng.type = 'hidden';
                        lng.name = 'longitude';
                        lng.value = position.coords.longitude;
                        form.appendChild(lng);

                        const accuracy = document.createElement('input');
                        accuracy.type = 'hidden';
                        accuracy.name = 'accuracy';
                        accuracy.value = position.coords.accuracy;
                        form.appendChild(accuracy);

                        document.body.appendChild(form);
                        message.textContent = 'Lokasi berhasil didapatkan.';
                        form.submit();
                    }, function(error) {
                        message.classList.remove('hidden');
                        message.textContent =
                            'Lokasi tidak dapat diperoleh. Pastikan GPS aktif dan izin lokasi diberikan.';
                    }, {
                        enableHighAccuracy: true,
                        timeout: 20000,
                        maximumAge: 0,
                    });
                });
            });
        });
    </script>
@endsection
