<?php

namespace App\Http\Controllers\Athlete;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\TrainingSchedule;
use App\Models\TrainingSession;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AthleteDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $athlete = $user->athlete()->with('clubs')->first();

        $clubs = $athlete?->clubs ?? collect();
        $clubIds = $athlete ? $athlete->clubs()->pluck('clubs.id')->all() : [];

        // Today's sessions for athlete's clubs
        $todaySessions = TrainingSession::query()
            ->with(['trainingSchedule.club', 'trainingSchedule.trainingLocation'])
            ->where('status', 'active')
            ->whereDate('date', today())
            ->whereHas('trainingSchedule', function ($query) {
                $query->where('status', 'active');
            })
            ->whereHas('trainingSchedule.trainingLocation', function ($query) {
                $query->where('status', 'active');
            })
            ->orderBy('start_time')
            ->get()
            ->filter(function (TrainingSession $session) use ($clubIds) {
                return in_array($session->trainingSchedule->club_id, $clubIds, true);
            });

        // Upcoming sessions
        $upcomingSessions = TrainingSession::query()
            ->with(['trainingSchedule.club', 'trainingSchedule.trainingLocation'])
            ->where('status', 'active')
            ->whereHas('trainingSchedule', fn($query) => $query->where('status', 'active'))
            ->whereHas('trainingSchedule.trainingLocation', fn($query) => $query->where('status', 'active'))
            ->whereDate('date', '>=', today())
            ->orderBy('date')
            ->orderBy('start_time')
            ->limit(5)
            ->get()
            ->filter(function (TrainingSession $session) use ($clubIds) {
                return in_array($session->trainingSchedule->club_id, $clubIds, true);
            });

        // Attendance stats
        $totalHadir = Attendance::query()
            ->where('athlete_id', $athlete?->id ?? 0)
            ->where('attendance_status', 'hadir')
            ->count();

        $totalIzin = Attendance::query()
            ->where('athlete_id', $athlete?->id ?? 0)
            ->where('attendance_status', 'izin')
            ->count();

        // Alpha = total past sessions (for athlete's clubs) minus attendance records
        $totalPastSessions = TrainingSession::query()
            ->where('status', 'active')
            ->whereDate('date', '<', today())
            ->whereHas('trainingSchedule', function ($query) use ($clubIds) {
                $query->where('status', 'active')
                    ->whereIn('club_id', $clubIds);
            })
            ->count();

        // Also count today's sessions that have already ended
        $todayEndedSessions = TrainingSession::query()
            ->where('status', 'active')
            ->whereDate('date', today())
            ->where('end_time', '<', now()->format('H:i:s'))
            ->whereHas('trainingSchedule', function ($query) use ($clubIds) {
                $query->where('status', 'active')
                    ->whereIn('club_id', $clubIds);
            })
            ->count();

        $totalShouldAttend = $totalPastSessions + $todayEndedSessions;
        $totalAttendanceRecords = Attendance::query()
            ->where('athlete_id', $athlete?->id ?? 0)
            ->whereIn('attendance_status', ['hadir', 'izin'])
            ->count();

        $totalAlpha = max(0, $totalShouldAttend - $totalAttendanceRecords);

        // Recent attendances
        $recentAttendances = Attendance::query()
            ->with(['trainingSession.trainingSchedule.club', 'trainingLocation'])
            ->where('athlete_id', $athlete?->id ?? 0)
            ->orderByDesc('check_in_at')
            ->limit(5)
            ->get();

        return view('athlete.dashboard', compact(
            'athlete',
            'clubs',
            'todaySessions',
            'upcomingSessions',
            'recentAttendances',
            'totalHadir',
            'totalAlpha',
            'totalIzin',
        ));
    }
}
