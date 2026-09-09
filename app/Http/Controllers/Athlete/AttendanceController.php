<?php

namespace App\Http\Controllers\Athlete;

use App\Http\Controllers\Controller;
use App\Http\Requests\AthleteAttendanceStoreRequest;
use App\Models\Athlete;
use App\Models\Attendance;
use App\Models\TrainingLocation;
use App\Models\TrainingSession;
use App\Services\LocationDistanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(protected LocationDistanceService $distanceService) {}

    public function index(Request $request): View
    {
        $athlete = $request->user()->athlete()->with('clubs')->firstOrFail();
        $sessions = TrainingSession::query()
            ->with(['trainingSchedule.club', 'trainingSchedule.trainingLocation'])
            ->where('status', 'active')
            ->whereDate('date', '>=', today())
            ->whereHas('trainingSchedule', fn($query) => $query->where('status', 'active'))
            ->whereHas('trainingSchedule.trainingLocation', fn($query) => $query->where('status', 'active'))
            ->get()
            ->filter(function (TrainingSession $session) use ($athlete) {
                $clubIds = $athlete->clubs()->pluck('clubs.id')->all();

                return in_array($session->trainingSchedule->club_id, $clubIds, true);
            });

        return view('athlete.attendance', compact('sessions', 'athlete'));
    }

    public function store(AthleteAttendanceStoreRequest $request): RedirectResponse
    {
        $athlete = $request->user()->athlete()->firstOrFail();

        $session = TrainingSession::query()
            ->with(['trainingSchedule.trainingLocation', 'trainingSchedule.club'])
            ->whereKey($request->input('training_session_id'))
            ->firstOrFail();

        $trainingLocation = $request->filled('training_location_id')
            ? TrainingLocation::query()->findOrFail($request->integer('training_location_id'))
            : $session->trainingSchedule->trainingLocation;

        $validationResult = $this->assertSessionIsValidForAthlete($athlete, $session);
        if ($validationResult !== true) {
            return back()->withErrors(['training_session_id' => $validationResult]);
        }

        if ($trainingLocation->status !== 'active') {
            return back()->withErrors(['training_location_id' => 'Lokasi latihan yang dipilih tidak aktif.']);
        }

        $distance = $this->distanceService->calculate(
            (float) $request->input('latitude'),
            (float) $request->input('longitude'),
            (float) $trainingLocation->latitude,
            (float) $trainingLocation->longitude,
        );

        if ($athlete->attendances()->where('training_session_id', $session->id)->exists()) {
            return back()->withErrors(['training_session_id' => 'Anda sudah melakukan presensi pada sesi ini.']);
        }

        $locationStatus = $this->distanceService->resolveLocationStatus($distance, (float) $trainingLocation->radius);

        $attendance = Attendance::query()->create([
            'athlete_id' => $athlete->id,
            'training_session_id' => $session->id,
            'training_location_id' => $trainingLocation->id,
            'attendance_status' => 'hadir',
            'check_in_at' => now(),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'gps_accuracy' => $request->input('accuracy'),
            'distance_from_location' => round($distance, 2),
            'location_status' => $locationStatus,
            'note' => $locationStatus === 'perlu_verifikasi' ? 'GPS accuracy kurang baik atau lokasi di luar radius.' : null,
        ]);

        return redirect()->route('athlete.attendances')->with('success', $locationStatus === 'sesuai'
            ? 'Presensi berhasil.'
            : 'Presensi tercatat, tetapi lokasi berada di luar radius latihan dan perlu diverifikasi.');
    }

    public function history(Request $request): View
    {
        $athlete = $request->user()->athlete()->firstOrFail();

        $attendances = Attendance::query()
            ->with(['trainingSession.trainingSchedule.club', 'trainingLocation'])
            ->where('athlete_id', $athlete->id)
            ->orderByDesc('check_in_at')
            ->paginate(10);

        return view('athlete.history', compact('attendances'));
    }

    protected function assertSessionIsValidForAthlete(Athlete $athlete, TrainingSession $session): bool|string
    {
        $clubIds = $athlete->clubs()->pluck('clubs.id')->all();

        $trainingSchedule = $session->trainingSchedule;
        $trainingLocation = $trainingSchedule?->trainingLocation;

        if (! $trainingSchedule || ! $trainingLocation) {
            return 'Sesi latihan tidak valid.';
        }

        if (! in_array($trainingSchedule->club_id, $clubIds, true)) {
            return 'Atlet tidak terdaftar pada klub sesi ini.';
        }

        if ($session->status !== 'active') {
            return 'Sesi latihan tidak aktif.';
        }

        if ($trainingSchedule->status !== 'active') {
            return 'Jadwal latihan tidak aktif.';
        }

        if ($trainingLocation->status !== 'active') {
            return 'Lokasi latihan tidak aktif.';
        }

        $now = now();
        $start = now()->parse($session->date . ' ' . $session->start_time);
        $end = now()->parse($session->date . ' ' . $session->end_time);

        if ($now->lt($start) || $now->gt($end)) {
            return 'Waktu presensi tidak sesuai dengan sesi latihan.';
        }

        return true;
    }
}
