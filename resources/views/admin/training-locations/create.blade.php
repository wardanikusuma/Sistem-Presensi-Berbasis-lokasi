@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <p class="text-sm font-medium text-indigo-600">Master Data</p>
            <h1 class="text-3xl font-bold text-slate-900">Tambah Lokasi Latihan</h1>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <div class="mb-4 rounded-md border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                Latitude dan longitude akan dipakai untuk validasi GPS presensi atlet saat absensi.
            </div>

            <form action="{{ route('admin.training-locations.store') }}" method="POST" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Nama</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Alamat</label>
                        <textarea name="address" rows="3"
                            class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Latitude</label>
                        <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude') }}"
                            class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                        @error('latitude')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Longitude</label>
                        <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude') }}"
                            class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                        @error('longitude')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Radius (meter)</label>
                        <input type="number" step="any" min="1" name="radius" id="radius" value="{{ old('radius', 100) }}"
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
                            <option value="sekolah" {{ old('type') === 'sekolah' ? 'selected' : '' }}>sekolah</option>
                            <option value="luar_sekolah" {{ old('type') === 'luar_sekolah' ? 'selected' : '' }}>
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
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active
                            </option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Peta Lokasi</label>
                        <div id="map" style="height: 350px; z-index: 1;" class="w-full rounded-md border border-slate-300"></div>
                        <p class="mt-1 text-xs text-slate-500">Anda dapat menggeser marker atau mengklik pada peta untuk menyesuaikan koordinat lokasi.</p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('admin.training-locations.index') }}"
                        class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700">Batal</a>
                    <button type="submit"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            const radiusInput = document.getElementById('radius');

            // Initialize Map
            // Default center if no value yet (e.g. Jakarta)
            let currentLat = latInput.value ? parseFloat(latInput.value) : -6.200000;
            let currentLng = lngInput.value ? parseFloat(lngInput.value) : 106.816666;
            let currentRadius = radiusInput.value ? parseFloat(radiusInput.value) : 100;

            const map = L.map('map').setView([currentLat, currentLng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            let marker = L.marker([currentLat, currentLng], { draggable: true }).addTo(map);
            let circle = L.circle([currentLat, currentLng], {
                color: '#4f46e5',
                fillColor: '#4f46e5',
                fillOpacity: 0.2,
                radius: currentRadius
            }).addTo(map);

            function updateMapAndInputs(lat, lng, radius) {
                latInput.value = lat;
                lngInput.value = lng;
                marker.setLatLng([lat, lng]);
                circle.setLatLng([lat, lng]);
                circle.setRadius(radius);
                map.setView([lat, lng]);
            }

            // Marker drag event
            marker.on('dragend', function(e) {
                const pos = marker.getLatLng();
                updateMapAndInputs(pos.lat, pos.lng, parseFloat(radiusInput.value) || 100);
            });

            // Map click event
            map.on('click', function(e) {
                updateMapAndInputs(e.latlng.lat, e.latlng.lng, parseFloat(radiusInput.value) || 100);
            });

            // Input change events
            function onInputChange() {
                const lat = parseFloat(latInput.value) || currentLat;
                const lng = parseFloat(lngInput.value) || currentLng;
                const r = parseFloat(radiusInput.value) || 100;
                marker.setLatLng([lat, lng]);
                circle.setLatLng([lat, lng]);
                circle.setRadius(r);
                map.setView([lat, lng]);
            }

            latInput.addEventListener('input', onInputChange);
            lngInput.addEventListener('input', onInputChange);
            radiusInput.addEventListener('input', onInputChange);

            // Fetch Geolocation if inputs are empty
            if (!latInput.value && !lngInput.value) {
                const originalLatType = latInput.type;
                const originalLngType = lngInput.type;
                latInput.type = 'text';
                lngInput.type = 'text';
                latInput.value = 'Sedang memuat...';
                lngInput.value = 'Sedang memuat...';

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        latInput.type = originalLatType;
                        lngInput.type = originalLngType;
                        updateMapAndInputs(position.coords.latitude, position.coords.longitude, parseFloat(radiusInput.value) || 100);
                    }, function(error) {
                        console.error("Error getting location:", error);
                        latInput.type = originalLatType;
                        lngInput.type = originalLngType;
                        latInput.value = '';
                        lngInput.value = '';
                    });
                } else {
                    console.warn("Geolocation is not supported by this browser.");
                    latInput.type = originalLatType;
                    lngInput.type = originalLngType;
                    latInput.value = '';
                    lngInput.value = '';
                }
            }
        });
    </script>
@endsection
