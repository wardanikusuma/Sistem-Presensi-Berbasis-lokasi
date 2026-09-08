<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
use App\Models\Attendance;
use App\Models\Club;
use App\Models\TrainingLocation;
use App\Models\TrainingSchedule;
use App\Models\TrainingSession;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'totalActiveAthletes' => Athlete::query()->where('status', 'active')->count(),
            'totalActiveClubs' => Club::query()->where('status', 'active')->count(),
            'totalActiveLocations' => TrainingLocation::query()->where('status', 'active')->count(),
            'totalActiveSchedules' => TrainingSchedule::query()->where('status', 'active')->count(),
            'totalTrainingSessions' => TrainingSession::query()->count(),
            'totalAttendances' => Attendance::query()->count(),
            'totalNeedVerification' => Attendance::query()->where('location_status', 'perlu_verifikasi')->count(),
        ];

        $clubs = Club::query()
            ->withCount([
                'athletes as active_athletes_count' => fn ($query) => $query->where('status', 'active'),
                'trainingSchedules as active_schedules_count' => fn ($query) => $query->where('status', 'active'),
            ])
            ->orderBy('name')
            ->get();

        $upcomingSessions = TrainingSession::query()
            ->with(['trainingSchedule.club', 'trainingSchedule.trainingLocation'])
            ->whereDate('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('start_time')
            ->limit(5)
            ->get();

        $todaySessions = TrainingSession::query()
            ->with(['trainingSchedule.club', 'trainingSchedule.trainingLocation', 'attendances'])
            ->whereDate('date', today())
            ->orderBy('start_time')
            ->get();

        $attendanceSummary = [
            'hadir' => Attendance::query()->where('attendance_status', 'hadir')->count(),
            'terlambat' => Attendance::query()->where('attendance_status', 'terlambat')->count(),
            'izin' => Attendance::query()->where('attendance_status', 'izin')->count(),
            'sakit' => Attendance::query()->where('attendance_status', 'sakit')->count(),
            'alpa' => Attendance::query()->where('attendance_status', 'alpa')->count(),
            'perlu_verifikasi' => Attendance::query()->where('location_status', 'perlu_verifikasi')->count(),
        ];

        $locationMonitoring = [
            'sesuai' => Attendance::query()->where('location_status', 'sesuai')->count(),
            'perlu_verifikasi' => Attendance::query()->where('location_status', 'perlu_verifikasi')->count(),
        ];

        return view('admin.dashboard', compact(
            'stats',
            'clubs',
            'upcomingSessions',
            'todaySessions',
            'attendanceSummary',
            'locationMonitoring',
        ));
    }
}
