<?php

namespace App\Http\Controllers\Athlete;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
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

        $validSessions = TrainingSession::query()
            ->with(['trainingSchedule.club', 'trainingSchedule.trainingLocation'])
            ->where('status', 'active')
            ->whereDate('date', today())
            ->whereHas('trainingSchedule', function ($query) {
                $query->where('status', 'active');
            })
            ->whereHas('trainingSchedule.trainingLocation', function ($query) {
                $query->where('status', 'active');
            })
            ->get()
            ->filter(function (TrainingSession $session) use ($athlete) {
                if (! $athlete) {
                    return false;
                }

                $clubIds = $athlete->clubs()->pluck('clubs.id')->all();

                return in_array($session->trainingSchedule->club_id, $clubIds, true);
            });

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
            ->filter(function (TrainingSession $session) use ($athlete) {
                if (! $athlete) {
                    return false;
                }

                $clubIds = $athlete->clubs()->pluck('clubs.id')->all();

                return in_array($session->trainingSchedule->club_id, $clubIds, true);
            });

        $recentAttendances = Attendance::query()
            ->with(['trainingSession.trainingSchedule.club', 'trainingLocation'])
            ->where('athlete_id', $athlete?->id ?? 0)
            ->orderByDesc('check_in_at')
            ->limit(5)
            ->get();

        return view('athlete.dashboard', compact('athlete', 'clubs', 'validSessions', 'upcomingSessions', 'recentAttendances'));
    }
}
